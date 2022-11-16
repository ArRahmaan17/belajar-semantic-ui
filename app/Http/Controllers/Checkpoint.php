<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Checkpoint as ModelsCheckpoint;
use App\Models\Manifest;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

use function PHPSTORM_META\type;

class Checkpoint extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $tokenAuthentication)
    {
        if ($tokenAuthentication . '-' . env('HALF_SECRET_KEY') != env('SECRET_KEY')) {
            return view('unauthorization');
        }
        $manifestCode = ($request->query('manifest_code') !== null) ? $request->query('manifest_code') : '';
        return view('checkpoints.index', compact('manifestCode'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $responses = Manifest::getManifest($request['manifest-code'], $request['vendor-code']);
        if (!$responses['status']) {
            return response($responses, 404);
        }
        return response($responses, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['description' => 'required', 'recipient' => 'required']);
        $branchData = Branch::where('kodecabang', '=', $request->branchCode)->where('id', '=', $request->branchId)->first();
        $data = [
            'transaction_code' => $request->manifestCode,
            'branch_code' => $request->branchCode,
            'branch_id' => $request->branchId,
            'recipient' => $request->recipient,
            'incoming_at' => date('Y-m-d H:i:s'),
            'description' => $request->description,
            'message' => $branchData->temp_manifest,
            'ip_address' => $request->ip(),
        ];
        $dataInserted =  ModelsCheckpoint::create($data);
        $detailManifest = Manifest::detailManifest($request->manifestCode);
        if (!empty($detailManifest)) {
            foreach ($detailManifest as $transaction) {
                $row = [
                    'idtransaksi' => $transaction->idtrans,
                    'notransaksi' => $transaction->notransaksi,
                    'kdcabang' => $dataInserted->branch_code,
                    'idcabang' => $dataInserted->branch_id,
                    'tanggal' => date('Y-m-d'),
                    'jam' => date('H:i:s'),
                    'keterangan' => $branchData->temp_mansuk,
                    'user' => $dataInserted->branch_code,
                    'tampil' => 1,
                    'posisi' => $branchData->jenis,
                    'masuk' => 1,
                    'ipaddres' => "" . $dataInserted->ip_address,
                    'pc' => 1,
                    'manifest' => 1,
                ];
                Tracking::createTrackingHistory($row);
            }
        }
        $manifest[] = [
            'kdcabang' => $dataInserted->branch_code,
            'idcabang' => $dataInserted->branch_id,
            'tanggal' => date('Y-m-d'),
            'jam' => date('H:i:s'),
            'keterangan' => $branchData->temp_manifest,
            'user' => $dataInserted->branch_code,
            'tampil' => 1,
            'posisi' => $branchData->jenis,
            'masuk' => 1,
            'kdmanifest' => $dataInserted->transaction_code,
            'ipaddres' => $dataInserted->ip_address,
            'pc' => 1,
            'manifest' => 1,
        ];
        $responsesInsertedTrackingHistory = Tracking::createTrackingHistory($manifest);
        $response = [
            'status' => 'success',
            'message' => 'checkpoint successfully saved',
        ];
        if (!$responsesInsertedTrackingHistory) {
            $response = [
                'status' => 'failed',
                'message' => 'checkpoint failed to be saved',
            ];
        }
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $listManifests = ModelsCheckpoint::getAllCheckpointManifest($request->branchCode, $request->limit, $request->lastId);
        $table = "";
        $lastId = 0;
        foreach ($listManifests['dataFiltered'] as $manifest) {
            $table .= "<tr>";
            $status = "<div class='ui green inverted horizontal label'>Masuk</div>";
            $action = "<div class='ui tiny buttons'><button class='ui yellow button'>Depart</button><div class='or'></div><button class='ui positive button'>Handling</button></div>";
            if ($manifest->outcoming_at != null) {
                $action = "";
                $status = "<div class='ui red horizontal label'>Keluar</div>";
            }
            $table .= "<td>" . $manifest->transaction_code . " " . $status . "</td>";
            $table .= "<td>" . $manifest->agen_alamat . "</td>";
            $table .= "<td>" . $action . "</td>";
            $table .= "</tr>";
            $lastId = $manifest->id;
        }
        $response = [
            'table' => $table,
            'lastId' => $lastId,
            'countAll' => $listManifests['countAll'],
        ];
        return response()->json($response, 200);
    }

    public function fileUpload(Request $request)
    {
        $manifestCode = $request['current-manifest-code'];
        $lastInsertedManifest = ModelsCheckpoint::where('transaction_code', '=', $manifestCode)->orderBy('id', 'desc')->first();
        $files = $request->file;
        $filesUpload = array();
        $index = 0;
        foreach ($files as $file) {
            $filename = $index . date('Y-m-d-H:i:s') . $manifestCode . "." . $file->extension();
            $path = $file->storeAs('checkpoints', $filename);
            $filesUpload[] = $path;
            $index++;
        }
        $jsonFilesUpload = json_encode($filesUpload);
        if (!ModelsCheckpoint::where('id', '=', $lastInsertedManifest->id)->where('transaction_code', '=', $manifestCode)->update(['photos' => $jsonFilesUpload])) {
            $response = [
                'status' => 'failed',
                'message' => 'we are failed to upload your checkpoint images',
            ];
            return response()->json($response, 402);
        } else {
            $response = [
                'status' => 'success',
                'message' => 'Your manifest images successfully uploaded!',
            ];
            return response()->json($response, 200);
        };
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
