<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/semantic.min.js"
    integrity="sha512-Xo0Jh8MsOn72LGV8kU5LsclG7SUzJsWGhXbWcYs2MAmChkQzwiW/yTQwdJ8w6UA9C6EVG18GHb/TrYpYCjyAQw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/components/modal.min.js"
    integrity="sha512-OGERHcA15jiPgUEtlw33PspOK3kIgHsuzQl5DMpLhKJumGi+EWA4yUa2FaawonaQ0KGk44NV+fAQiPMHPUXQQw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/components/tab.min.js"
    integrity="sha512-2XXxdqmnro82iCy9m+XNz4S751T5rkSTCsHum/ZFNFcSnrYYFUNmB8kV9CJKJU4RrHhUszM4kOfMDLntUSnF1A=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.5.0/components/dimmer.min.js"
    integrity="sha512-Cq2FPdGnAkxMM7AZj30Cvi0myyCNE1q+ZRMEQCUTlGwF+gHGStYwknXFu7VISOXFy1G2fNswlRIGj9LjjlXurA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src={{ asset('assets/modules/dropzonejs/min/dropzone.min.js') }}></script>
<script src={{ asset('assets/js/alertify.min.js') }}></script>

<!-- Template JS File -->
<script src={{ asset('assets/js/scripts.js') }}></script>
<script src={{ asset('assets/js/custom.js') }}></script>
<script>
    let _token = $("meta[name='token']").attr('content');
    let weight = 0;
    let coli = 0;
    let volume = 0;
    let filesUpload;
    $(document).ready(function() {
        alertify.set('notifier', 'position', 'top-center');
        alertify.defaults.transition = "slide";
        alertify.defaults.theme.ok = "ui positive button";
        alertify.defaults.theme.cancel = "ui black button";
        $(".item[data-tab='tab-checkpoint']").click(function() {
            alertify.confirm('Please Confirm Your Action',
                'Are you sure to do the checkpoint again ?',
                function() {
                    alertify.notify('We Are Prosessing Your Checkpoint')
                    clearAll();
                    $('.ui.modal').modal({
                        blurring: true,
                        inverted: true,
                        closable: false,
                        transition: 'slide',
                        duration: 450,
                    }).modal('show');
                },
                function() {
                    alertify.error('Your Are Canceling The Action')
                });

        })
        setTimeout(() => {
            $('.ui.modal').modal({
                blurring: true,
                inverted: true,
                closable: false,
                transition: 'slide',
                duration: 450,
            }).modal('show');

        }, 250);
        $('.tabular.menu .item').tab();
        $("#manifest-search-button").click(function() {
            requiredInputs = [
                'vendor-code,vendor-input',
                'manifest-code,manifest-input'
            ];
            let payload = {};
            requiredInputs.forEach(input => {
                input = input.split(',');
                if ($(`#${input[0]}`).val() == '') {
                    $(`#${input[1]}`).addClass('error');
                    $(`#${input[1]}`).transition({
                        debug: true,
                        animation: 'shake',
                        duration: 500
                    })
                } else {
                    payload[`${input[0]}`] = $(`#${input[0]}`).val();
                    $(`#${input[1]}`).removeClass('error');
                }
            });
            payload['_token'] = _token;
            $.ajax({
                type: "POST",
                url: `<?= url('/checkpoint/show') ?>`,
                data: {
                    ...payload
                },
                dataType: "JSON",
                beforeSend: () => {
                    $('#manifest-search-button').addClass('loading');
                    $('#manifest-search-button').addClass('disabled');
                    $('#dimmer-modal').addClass('active');
                },
                success: function(response) {
                    if (response.status) {
                        data = JSON.parse(response.data)
                        dataDetail = JSON.parse(response.details);
                        $.map(dataDetail, function(detail) {
                            weight += detail['berat'];
                            coli += detail['colly'];
                            volume += detail['volume'];
                        });
                        setDataToManifestForm(data);
                        setDetailToManifestDetailTable(dataDetail);
                        $('.ui.modal').modal('hide');
                        $('#container-checkpoint').removeClass('none')
                        $('#manifest-placeholder').addClass('none')
                        $('#manifest-search-button').removeClass('loading');
                        alertify.success(response.message);
                        $('#dimmer-modal').removeClass('active');
                        $('#manifest-search-button').removeClass('disabled');
                    }
                },
                error: response => {
                    $('#manifest-search-button').removeClass('loading');
                    $('#manifest-search-button').removeClass('disabled');
                    $('#dimmer-modal').removeClass('active');
                }
            });
        });
        $("#fileupload").dropzone({
            url: "/checkpoint/upload-file",
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 4, // MB
            acceptedFiles: "image/*",
            uploadMultiple: true,
            parallelUploads: 10,
            addRemoveLinks: true,
            autoProcessQueue: false,
            init: function() {
                var myDropzone = this;
                this.element.querySelector("button[type=submit]").addEventListener("click",
                    function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        myDropzone.processQueue();
                    });
                this.on("successmultiple", function(files, response) {
                    $("#button-checkpoint-manifest").removeClass('loading');
                    alertify.success('We Are Successfully Process Your Checkpoint');
                    myDropzone.removeAllFiles();
                });
            }
        });
        $("#button-checkpoint-manifest").click(function() {
            alertify.confirm('Please Confirm Your Action',
                'Are Your Sure To Save Your Checkpoint Prosess',
                function() {
                    alertify.notify('We Are Prosessing Your Checkpoint')
                    saveCheckpoint();
                },
                function() {
                    alertify.error('Your Are Canceling The Checkpoint')
                });
        });

        function saveCheckpoint() {
            let manifestCode = $("#checkpoint-manifest-code").val();
            let description = $("#checkpoint-manifest-description").val();
            let recipient = $("#checkpoint-manifest-recipient").val();
            let manifestId = $("#checkpoint-manifest-id").val();
            let branchId = $("#checkpoint-manifest-branch-id").val();
            let branchCode = $("#checkpoint-manifest-branch-code").val();
            let payload = {
                _token,
                manifestId,
                manifestCode,
                description,
                recipient,
                branchCode,
                branchId,
            };

            $.ajax({
                method: "POST",
                url: "/checkpoint/store",
                data: {
                    ...payload
                },
                dataType: "JSON",
                beforeSend: function() {
                    $("#button-checkpoint-manifest").addClass('loading');
                },
                success: function(response) {
                    $("button[type=submit]").click().promise().then(() => {
                        $("#button-checkpoint-manifest").removeClass('loading');
                        clearAll();
                        changeTab();
                    });
                },
                error: (errors) => {
                    errors = Object.values(errors.responseJSON.errors)
                    errors.forEach(error => {
                        alertify.error(error[0]).dismissOthers();
                    });
                    $("#button-checkpoint-manifest").removeClass('loading');
                }
            });
        }
    });

    function changeTab() {
        $("div[data-tab='tab-manifest-checkpoint']").addClass('active');
        $("div[data-tab='tab-checkpoint']").removeClass('active');
    }

    function clearAll() {
        requiredClear = [
            'checkpoint-manifest-code',
            'checkpoint-manifest-id',
            'checkpoint-manifest-branch-code',
            'checkpoint-manifest-branch-id',
            'checkpoint-manifest-weight',
            'checkpoint-manifest-origin',
            'checkpoint-manifest-coli',
            'checkpoint-manifest-destination',
            'checkpoint-manifest-volume',
            'checkpoint-manifest-description',
            'checkpoint-manifest-recipient',
        ];
        requiredClear.forEach(field => {
            $(`#${field}`).val('');
        });
        $("#bodyTableDetail").html('');
        $("#container-checkpoint").addClass('none')
        $("#manifest-placeholder").removeClass('none')
    }

    function setDataToManifestForm(data) {
        $("#checkpoint-manifest-id").val(data.id);
        $("#checkpoint-manifest-branch-code").val(data.kodeagen);
        $("#checkpoint-manifest-branch-id").val(data.idagen);
        $("#checkpoint-manifest-code").val(data.kodemanifest);
        $("#current-manifest-code").val(data.kodemanifest);
        $("#checkpoint-manifest-weight").val(weight);
        $("#checkpoint-manifest-origin").val(data.nama_cabang);
        $("#checkpoint-manifest-coli").val(coli);
        $("#checkpoint-manifest-destination").val(data.agen_nama + ' ' + data.agen_alamat);
        $("#checkpoint-manifest-volume").val(volume);
    }

    function setDetailToManifestDetailTable(detailManifest) {
        if (detailManifest.length == 0) {
            htmlTable =
                `<tr><td style='text-align:center;' colspan='5'> Data Detail Tidak Ditemukan </td></tr>`;
        } else {
            htmlTable = `<tr>`;
            index = 0;
            detailManifest.forEach(detail => {
                htmlTable += `<td> ${index}</td>`;
                htmlTable += `<td> ${detail.notrans}</td>`;
                htmlTable += `<td> ${detail.tujuan}</td>`;
                htmlTable += `<td> ${detail.berat} ${detail.colly} ${detail.volume}</td>`;
                htmlTable += `<td> ${detail.keterangan}</td>`;
            });
            htmlTable += `</tr>`;
        }
        $("#bodyTableDetail").html(htmlTable);
    }
</script>
