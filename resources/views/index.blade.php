<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kantin Wikrama</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        .full-height {
            height: 100vh;
        }
        .centered-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            font-size: 2rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid full-height">
        <div class="row h-100">
            <div class="col-12 d-flex align-items-center justify-content-center">
                <div class="card w-50">
                    <div class="card-body centered-content">
                        <h4 class="card-title">Absen Pagi</h4>
                        <form method="POST" action="" class="forms-sample" id="form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="nis" id="nis">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 24 24">
                                    <path fill="#000000" d="M20 20H4c-1.654 0-3-1.346-3-3V7c0-1.654 1.346-3 3-3h16c1.654 0 3 1.346 3 3v10c0 1.654-1.346 3-3 3M4 6c-.551 0-1 .449-1 1v10c0 .551.449 1 1 1h16c.551 0 1-.449 1-1V7c0-.551-.449-1-1-1zm6 9H6a1 1 0 1 1 0-2h4a1 1 0 1 1 0 2m0-4H6a1 1 0 1 1 0-2h4a1 1 0 1 1 0 2"/>
                                    <circle cx="16" cy="10.5" r="2" fill="#000000"/>
                                    <path fill="#000000" d="M16 13.356c-1.562 0-2.5.715-2.5 1.429c0 .357.938.715 2.5.715c1.466 0 2.5-.357 2.5-.715c0-.714-.98-1.429-2.5-1.429"/>
                                </svg>                                                    
                                <h5>Pindai Kartu Pelajar</h5>
                                <h6>Silahkan lakukan pemindaian kartu pelajar pada perangkat</h6>
                                <p id="cardUidDisplay"></p>
                            </div>
                        </form>
                        <!-- Button to trigger modal -->
                        <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#attendanceShowModal">
                            Input NIS
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modal --}}
    <div class="modal fade" id="attendanceShowModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Form Input Absen Pagi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="p-3" method="POST" action="" id="form2">
                    @csrf
                    <div class="mb-3">
                        <label for="nis-modal" class="form-label">NIS</label>
                        <input type="number" class="form-control" id="nis-modal" name="nis" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            let intervalId;

            // Start reading NFC card
            intervalId = setInterval(function() {
                $.ajax({
                    url: "{{ route('read.nfc') }}", 
                    type: 'GET',
                    success: function(response) {
                        if (response.status === 'success') {
                            let card_uid_length = response.card_uid.length;

                            if (card_uid_length === 8) {
                                $('#nis').val(response.card_uid);
                                clearInterval(intervalId);
                                $('#attendanceShowModal').modal('show'); // Show modal when a card UID is read
                            } else {
                                $('#cardUidDisplay').text('Card UID: Invalid Card UID');
                            }
                        } else {
                            $('#cardUidDisplay').text('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }, 1000);
        });
    </script>
</body>
</html>
