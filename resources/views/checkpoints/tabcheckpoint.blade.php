<div id="manifest-placeholder" class="ui fluid placeholder">
    <div class="full line"></div>
    <div class="full line"></div>
    <div class="full line"></div>
    <div class="full line"></div>
    <div class="image"></div>
    <div class="very short line"></div>
    <div class="image"></div>
</div>
<div id="container-checkpoint" class="none">
    <div class="ui centered grid container">
        <div class="column">
            <div class="ui medium form">
                <div class="two fields">
                    <div class="field">
                        <label>No Manifest</label>
                        <input id="checkpoint-manifest-code" readonly='' type="text">
                        <input type="hidden" id="checkpoint-manifest-id">
                        <input type="hidden" id="checkpoint-manifest-branch-code">
                        <input type="hidden" id="checkpoint-manifest-branch-id">
                    </div>
                    <div class="field">
                        <label>Berat</label>
                        <input id="checkpoint-manifest-weight" readonly='' type="text">
                    </div>
                </div>
                <div class="two fields">
                    <div class="field">
                        <label>Asal</label>
                        <input id="checkpoint-manifest-origin" readonly='' type="text">
                    </div>
                    <div class="field">
                        <label>Koli</label>
                        <input id="checkpoint-manifest-coli" readonly='' type="text">
                    </div>
                </div>
                <div class="two fields">
                    <div class="field">
                        <label>Kepada</label>
                        <input id="checkpoint-manifest-destination" readonly='' type="text">
                    </div>
                    <div class="field">
                        <label>Volum</label>
                        <input id="checkpoint-manifest-volume" readonly='' type="text">
                    </div>
                </div>
                <div class="two fields">
                    <div class="field">
                        <label>Keterangan</label>
                        <textarea id="checkpoint-manifest-description" rows="2"></textarea>
                    </div>
                    <div class="field">
                        <label>Penerima</label>
                        <input id="checkpoint-manifest-recipient" type="text">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Foto Checkpoint</h4>
                            </div>
                            <div class="card-body">
                                <form id="fileupload" method="POST" action="{{ url('/checkpoint/file-upload') }}"
                                    class="dropzone" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" id="current-manifest-code" name="current-manifest-code" />
                                    <button class="none" type="submit"></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button id="button-checkpoint-manifest" class="ui green button">CheckPoint</button>
            <table class="ui selectable celled blue table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nomer Resi</th>
                        <th>Tujuan</th>
                        <th>Berat/Koli/Volum</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody id="bodyTableDetail"></tbody>
            </table>
        </div>
    </div>
</div>
