<x-layout.app>

    <style>
        .header {
            align-items: center;
        }

        .logo-container {
            display: flex;
            align-items: center;
        }

        .logo {
            height: 80px;
            width: auto;
        }

        .text-container {
            margin-left: 15px;
        }

        .pt-name,
        .qc-name {
            margin: 0;
        }

        .center-space {
            flex-grow: 1;
        }

        .right-container {
            text-align: right;
        }

        .rights-container {
            display: flex;

            justify-content: flex-end;
        }

        .custom_background {
            background: linear-gradient(to right, #ff5733, #ff9900);
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
            width: 100%;
            max-width: 300px;
            /* Adjust the max-width as needed */
            margin: 0 auto;
            /* Center the container horizontally */
        }
    </style>


    <div class="content-wrapper">
        <div class="card table_wrapper">
            <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark ">
                <h2>Pemeriksaan Emplashment</h2>
            </div>


            <div class="header-container">


                <div class="header d-flex justify-content-center mt-3 mb-2 ml-3 mr-3">

                    <div class="logo-container">

                        <img src="{{ asset('img/Logo-SSS.png') }}" alt="Logo" class="logo">
                        <div class="text-container">
                            <div class="pt-name">PT. SAWIT SUMBERMAS SARANA, TBK</div>
                            <div class="qc-name">QUALITY CONTROL</div>
                        </div>

                    </div>
                    <div class="center-space"></div>
                    <div class="right-container">
                        <div class="date">
                            {{ csrf_field() }}
                            <input type="hidden" name="est" id="est" value="{{$est}}">
                            <select class="form-control" name="date" id="inputDate">
                                <option value="" disabled selected hidden>Pilih tanggal</option>
                                @foreach($date as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="ml-2 btn btn-primary mb-2" id="empData">Show</button>
                        </div>
                        <div class="afd mt-2"> ESTATE/ AFD : {{$est}}</div>
                        <div class="afd">Tahun/Bulan : <span id="selectedDate">{{ $tanggal }}</span></div>
                        <!-- <button id="back-to-data-btn" class="btn btn-primary" onclick="downloadpdf()">Download PDF</button> -->

                        <form action="{{ route('downloadPDF') }}" method="POST" class="form-inline" style="display: inline;" target="_blank" id="downloadPDF">
                            {{ csrf_field() }}
                            <input type="hidden" name="estPDF" id="estPDF" value="{{$est}}">
                            <input type="hidden" name="tglpdfnew" id="tglpdfnew">
                            <button type="submit" class="btn btn-primary" id="downloadpdf" disabled>
                                Download PDF
                            </button>
                        </form>

                        <form action="{{ route('downloadBAemp') }}" method="POST" class="form-inline" style="display: inline;" target="_blank" id="download-form">
                            {{ csrf_field() }}
                            <input type="hidden" name="estBA" id="estpdf" value="{{$est}}">
                            <input type="hidden" name="tglPDF" id="tglPDF">
                            <button type="submit" class="btn btn-primary" id="downloadba" disabled>
                                Download BA
                            </button>
                        </form>
                        <button id="back-to-data-btn" class="btn btn-primary" onclick="goBack()">Back to Home</button>
                    </div>

                </div>
            </div>
            <br>

            <div class="d-flex justify-content-end mr-3">
                <button class="btn btn-primary ms-auto" id="toggleButton">Preview Penilaian</button>
                @if (session('jabatan') && (session('jabatan') === 'Askep' || session('jabatan') === 'Manager' || session('jabatan') === 'Asisten'))
                <button class="btn btn-primary ms-auto ml-3" id="addnewimg">Tambah Foto Baru</button>
                @endif

            </div>


            <div class="mt-3 text-center" id="content" style="display: none; width: 100%;">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="perumahanx-tab" data-toggle="tab" href="#perumahanx" role="tab" aria-controls="perumahanx" aria-selected="true">Perumahan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="landscapex-tab" data-toggle="tab" href="#landscapex" role="tab" aria-controls="landscapex" aria-selected="false">Landscape</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="lingkunganx-tab" data-toggle="tab" href="#lingkunganx" role="tab" aria-controls="lingkunganx" aria-selected="false">Lingkungan</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="perumahanx" role="tabpanel" aria-labelledby="perumahanx-tab">
                        <div class="d-flex justify-content-center mt-3 mb-2 border border-dark">

                            <div class="d-flex justify-content-center mt-3 mb-2 border border-dark">
                                <table class="table table-primary" id="tabPerum">
                                    <thead>
                                        <!-- Your table header content -->
                                    </thead>
                                    <tbody>
                                        <!-- Your table body content -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="landscapex" role="tabpanel" aria-labelledby="landscapex-tab">
                        <div class="d-flex justify-content-center mt-3 mb-2 border border-dark">

                            <div class="d-flex justify-content-center mt-3 mb-2 border border-dark">
                                <table class="table table-primary" id="tablangscape">
                                    <thead>
                                        <!-- Your table header content -->
                                    </thead>
                                    <tbody>
                                        <!-- Your table body content -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="lingkunganx" role="tabpanel" aria-labelledby="lingkunganx-tab">
                        <div class="d-flex justify-content-center mt-3 mb-2 border border-dark">

                            <div class="d-flex justify-content-center mt-3 mb-2 border border-dark">
                                <table class="table table-primary" id="tablingk">
                                    <thead>
                                        <!-- Your table header content -->
                                    </thead>
                                    <tbody>
                                        <!-- Your table body content -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card table_wrapper">


                <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark  custom_background">
                    <h2>Temuan ESTATE</h2>
                </div>


                <style>
                    /* Add this CSS to create the hover effect */
                    .card-title,
                    .card-text {
                        opacity: 0;
                        /* Set the initial opacity to 0 to hide the title and text */
                        transition: opacity 0.3s ease-in-out;
                        /* Add a smooth transition effect */
                    }

                    .card:hover .card-title,
                    .card:hover .card-text {
                        opacity: 1;
                        /* Set the opacity to 1 on hover to show the title and text */
                    }
                </style>


                <div class="text-center mt-3 mb-2 border border-dark" id="perumahan">
                </div>

                <div class="text-center mt-3 mb-2 border border-dark" id="landscape">
                </div>

                <div class="text-center mt-3 mb-2 border border-dark" id="lingkungan">
                </div>
            </div>


            <div class="card table_wrapper">
                <div class="d-flex justify-content-center mt-3 mb-2 ml-3 mr-3 border border-dark custom_background">
                    <h2>Temuan AFDELING</h2>
                </div>
                <div class="text-center mt-3 mb-2 border border-dark" id="afd_rmh">
                </div>

                <div class="text-center mt-3 mb-2 border border-dark" id="afd_landscape">
                </div>
                <div class="text-center mt-3 mb-2 border border-dark" id="afd_lingkungan">
                </div>
            </div>
            <input type="file" id="fileInput" style="display: none;">
            <style>
                .btn-container {
                    display: flex;
                    gap: 10px;
                    /* Adjust the spacing between buttons as needed */
                }
            </style>



            <!-- Button to trigger the modal -->
            <!-- <button class="edit-btn btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">Edit Nilai</button> -->

            <!-- Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Nilai</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                            <form id="editForm">

                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3" style="margin:10px">
                                            <input type="hidden" class="form-control" id="id">
                                            <input type="hidden" class="form-control" id="type">
                                            <p style="text-align:center">Instalasi Air</p>
                                            <label for="nilai_1" class="form-label">Lancar Masuk (air dapat mengalir dengan lancar 24 jam ke dalam rumah) </label>
                                            <input type="number" class="form-control" id="nilai_1">
                                            <label for="nilai_2" class="form-label">Kontrol (Setiap bak mandi menggunakan pelampung)</label>
                                            <input type="number" class="form-control" id="nilai_2">
                                            <label for="nilai_3" class="form-label">Lancar Keluar (saluran pembuangan air lancar)</label>
                                            <input type="number" class="form-control" id="nilai_3">
                                        </div>

                                        <div class="mb-3" style="margin:10px">
                                            <p style="text-align:center">Kebersihan di dalam rumah</p>
                                            <label for="nilai_1" class="form-label">Kaca & ventilasi (bersih dan tidak rusak) </label>
                                            <input type="number" class="form-control" id="nilai_4">
                                            <label for="nilai_2" class="form-label">Plafon (bersih dan tidak rusak) </label>
                                            <input type="number" class="form-control" id="nilai_5">
                                            <label for="nilai_3" class="form-label">Dinding (bersih dan tidak rusak)</label>
                                            <input type="number" class="form-control" id="nilai_6">
                                            <label for="nilai_3" class="form-label">Lantai (bersih dan tidak rusak) </label>
                                            <input type="number" class="form-control" id="nilai_7">
                                            <label for="nilai_3" class="form-label">Kamar Mandi (bersih dan tidak rusak) </label>
                                            <input type="number" class="form-control" id="nilai_8">
                                        </div>
                                        <div class="mb-3" style="margin:10px">
                                            <p style="text-align:center">Listrik</p>
                                            <label for="nilai_1" class="form-label">Instalasi (aman dan rapi) </label>
                                            <input type="number" class="form-control" id="nilai_9">
                                        </div>
                                        <div class="mb-3" style="margin:10px">
                                            <p style="text-align:center">Alat Pemadam Api Ringan (APAR) </p>
                                            <label for="nilai_1" class="form-label">G2 -> 1 Unit; G4 -> 1 Unit; G6 -> 2 Unit; G10 -> 3 Unit </label>
                                            <input type="number" class="form-control" id="nilai_10">
                                        </div>
                                        <div class="mb-3" style="margin:10px">
                                            <p style="text-align:center">Halaman Rumah</p>
                                            <label for="nilai_1" class="form-label">Bersih</label>
                                            <input type="number" class="form-control" id="nilai_11">
                                        </div>
                                        <div class="mb-3" style="margin:10px">
                                            <p style="text-align:center">Jemuran</p>
                                            <label for="nilai_1" class="form-label">Rapi dan seragam </label>
                                            <input type="number" class="form-control" id="nilai_12">
                                        </div>
                                        <div class="mb-3" style="margin:10px">
                                            <p style="text-align:center">Estetika</p>
                                            <label for="nilai_1" class="form-label">Ditanami Bunga, Buah & sayur mayur </label>
                                            <input type="number" class="form-control" id="nilai_13">
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade" id="editModallandscape" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Nilai</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                            <form id="editFormlandcsape">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3" style="margin:10px">
                                            <input type="hidden" class="form-control" id="id">
                                            <input type="hidden" class="form-control" id="type">
                                            <p style="text-align:center">Material</p>
                                            <label for="nilai_1" class="form-label">Ornamen-ornamen penghias taman </label>
                                            <input type="number" class="form-control" id="nilai_l1">
                                        </div>
                                        <div class="mb-3" style="margin:10px">
                                            <p style="text-align:center">Komposisi Tanaman</p>
                                            <label for="nilai_2" class="form-label">Jumlah Tanaman Hias (diharapkan >5 jenis tanaman hias)</label>
                                            <input type="number" class="form-control" id="nilai_l2">
                                        </div>
                                        <div class="mb-3" style="margin:10px">
                                            <p style="text-align:center">Kondisi Fisik Tanaman</p>
                                            <label for="nilai_3" class="form-label">Kondisi Fisik Tanaman</label>
                                            <input type="number" class="form-control" id="nilai_l3">
                                            <label for="nilai_1" class="form-label">Kebersihan Lingkungan </label>
                                            <input type="number" class="form-control" id="nilai_l4">
                                        </div>
                                        <div class="mb-3" style="margin:10px">
                                            <p style="text-align:center">Design</p>
                                            <label for="nilai_2" class="form-label">Bentuk Taman: Simetris (1), Lengkung (2), Vertikal (3) </label>
                                            <input type="number" class="form-control" id="nilai_l5">
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade" id="editModallkungan" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Nilai</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                            <form id="editFormlingkn">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3" style="margin:10px">
                                            <input type="hidden" class="form-control" id="id">
                                            <input type="hidden" class="form-control" id="type">
                                            <p style="text-align:center">Filter Air </p>
                                            <label for="nilai_1" class="form-label">Tersedia 1 Unit untuk afdeling/estate yang tidak dialiri air bersih dari Pabrik </label>
                                            <input type="number" class="form-control" id="nilai_k1">
                                        </div>
                                        <div class="mb-3" style="margin:10px">
                                            <p style="text-align:center">Profil Tank</p>
                                            <label for="nilai_2" class="form-label">Kapasitas minimal setara 200 Liter untuk 1 pintu </label>
                                            <input type="number" class="form-control" id="nilai_k2">
                                        </div>
                                        <div class="mb-3" style="margin:10px">
                                            <p style="text-align:center">Tempat Penitipan Anak</p>
                                            <label for="nilai_3" class="form-label">Karpet (Kecuali TPA dengan Keramik) </label>
                                            <input type="number" class="form-control" id="nilai_k3">
                                            <label for="nilai_1" class="form-label">Mainan </label>
                                            <input type="number" class="form-control" id="nilai_k4">
                                            <label for="nilai_1" class="form-label">Pagar </label>
                                            <input type="number" class="form-control" id="nilai_k5">
                                        </div>
                                        <div class="mb-3" style="margin:10px">
                                            <p style="text-align:center">Musholla/Masjid</p>
                                            <label for="nilai_2" class="form-label">Ketersediaan air</label>
                                            <input type="number" class="form-control" id="nilai_k6">
                                            <label for="nilai_2" class="form-label">Saluran Pembuangan air </label>
                                            <input type="number" class="form-control" id="nilai_k7">
                                            <label for="nilai_2" class="form-label">Kebersihan lingkungan </label>
                                            <input type="number" class="form-control" id="nilai_k8">
                                        </div>
                                        <div class="mb-3" style="margin:10px">
                                            <p style="text-align:center">Drainase</p>
                                            <label for="nilai_2" class="form-label">Bersihir</label>
                                            <input type="number" class="form-control" id="nilai_k9">
                                            <label for="nilai_2" class="form-label">Lancar </label>
                                            <input type="number" class="form-control" id="nilai_k10">
                                        </div>
                                        <div class="mb-3" style="margin:10px">
                                            <p style="text-align:center">Ketersediaan Tong Sampah</p>
                                            <label for="nilai_2" class="form-label">Sedikitnya tersedia 1 tong sampah untuk 1 kopel rumah</label>
                                            <input type="number" class="form-control" id="nilai_k11">
                                        </div>
                                        <div class="mb-3" style="margin:10px">
                                            <p style="text-align:center">Tempat Pembuangan Akhir</p>
                                            <label for="nilai_2" class="form-label">Tidak ada ceceran sampah</label>
                                            <input type="number" class="form-control" id="nilai_k12">
                                            <label for="nilai_2" class="form-label">Tidak ada penumpukan sampah</label>
                                            <input type="number" class="form-control" id="nilai_k13">
                                        </div>
                                        <div class="mb-3" style="margin:10px">
                                            <p style="text-align:center">Parkir Motor</p>
                                            <label for="nilai_2" class="form-label">Tersedia bangunan khusus</label>
                                            <input type="number" class="form-control" id="nilai_k14">
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="myModalLabel">Image Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <img src="" alt="" class="img-fluid" id="modalImage">
                            <p id="modalComment"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.getElementById('toggleButton');
            const content = document.getElementById('content');

            toggleButton.addEventListener('click', function() {
                if (content.style.display === 'none') {
                    content.style.display = 'block';
                } else {
                    content.style.display = 'none';
                }
            });
        });

        var currentUserName = "{{ session('jabatan') }}";

        // console.log(listafd);

        function goBack() {
            // Save the selected tab to local storage
            localStorage.setItem('selectedTab', 'nav-data-tab');

            // Redirect to the target page
            window.location.href = "https://qc-apps.srs-ssms.com/dashboard_perum";
        }

        $('#empData').click(function() {
            Swal.fire({
                title: 'Loading',
                html: '<span class="loading-text">Mohon Tunggu...</span>',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            getTemuan();


        });
        // Function to handle image click event and display modal
        function modalimg(imageUrl, comment) {
            const modalImage = document.getElementById("modalImage");
            const modalComment = document.getElementById("modalComment");

            // Set the image source and comment in the modal
            modalImage.src = imageUrl;
            modalImage.alt = imageUrl; // Set alt as the image URL if alt is empty
            modalComment.textContent = `Temuan: ${comment}`;

            // Show the modal
            $('#myModal').modal('show');
        }

        function rumahupdate(Perumahan) {
            const container = document.getElementById("perumahan");
            const imageBaseUrl = "https://mobilepro.srs-ssms.com/storage/app/public/qc/perumahan/";
            const defaultImageUrl = "{{ asset('img/404img.png') }}"; // Use the asset function to get the correct URL
            if (Perumahan.length > 0) {
                // Create the heading
                const heading = document.createElement("div");
                heading.classList.add("text-center");
                heading.innerHTML = "<h1>Foto Temuan Perumahan</h1>";
                container.appendChild(heading);

                // Create the row container
                const rowContainer = document.createElement("div");
                rowContainer.classList.add("row", "justify-content-center");
                container.appendChild(rowContainer);

                // Iterate through the array data
                Perumahan.forEach((item) => {
                    const id = item[0];
                    const data = item[1];
                    const imageUrl = imageBaseUrl + data.foto_temuan_rmh;

                    // Create card structure
                    const card = document.createElement("div");
                    card.classList.add("col-md-6", "col-lg-3", "mb-3");

                    const cardInner = document.createElement("div");
                    cardInner.classList.add("card");

                    const image = new Image();
                    image.src = imageUrl;
                    image.alt = data.foto_temuan_rmh;
                    image.classList.add("card-img-top", "img-clickable");
                    image.setAttribute("data-image", imageUrl);
                    image.setAttribute("data-comment", data.komentar_temuan_rmh);
                    image.addEventListener("click", () => modalimg(imageUrl, data.komentar_temuan_rmh));

                    const cardBody = document.createElement("div");
                    cardBody.classList.add("card-body");

                    const title = document.createElement("h5");
                    title.classList.add("card-title", "text-right");
                    title.textContent = `Est: ${data.title}`;

                    const text = document.createElement("p");
                    text.classList.add("card-text", "text-left");
                    text.textContent = `Temuan: ${data.komentar_temuan_rmh}`;

                    // Construct card
                    cardBody.appendChild(title);
                    cardBody.appendChild(text);
                    cardInner.appendChild(image);
                    cardInner.appendChild(cardBody);
                    card.appendChild(cardInner);


                    rowContainer.appendChild(card);

                    if (currentUserName === 'Askep' || currentUserName === 'Manager' || currentUserName === 'Asisten') {
                        const buttonContainer = document.createElement("div");
                        buttonContainer.classList.add("btn-container");

                        const downloadLink = document.createElement("a");
                        downloadLink.href = "#"; // Set a placeholder link initially
                        downloadLink.innerHTML = '<i class="fas fa-download"></i> Download Image';
                        downloadLink.classList.add("btn", "btn-primary", "btn-sm");
                        buttonContainer.appendChild(downloadLink);

                        const uploading = document.createElement("a");
                        uploading.href = "#"; // Set a placeholder link initially
                        uploading.innerHTML = '<i class="fa fa-cloud-upload" aria-hidden="true"></i> Upload Image';
                        uploading.classList.add("btn", "btn-primary", "btn-sm");
                        buttonContainer.appendChild(uploading);

                        const deletes = document.createElement("a");
                        deletes.href = "#"; // Set a placeholder link initially
                        deletes.innerHTML = '<i class="fa fa-trash" aria-hidden="true"></i> Delete The Image';
                        deletes.classList.add("btn", "btn-primary", "btn-sm");
                        buttonContainer.appendChild(deletes);

                        const editkoment = document.createElement("a");
                        editkoment.href = "#"; // Set a placeholder link initially
                        editkoment.innerHTML = '<i class="fa fa-comments" aria-hidden="true"></i> Edit Komentar';
                        editkoment.classList.add("btn", "btn-primary", "btn-sm");
                        buttonContainer.appendChild(editkoment);
                        // Append the container to the card body
                        card.querySelector(".card-body").appendChild(buttonContainer);



                        deletes.addEventListener("click", () => {
                            // Display a confirmation dialog
                            Swal.fire({
                                title: 'Delete Confirmation',
                                text: 'Anda Yaking Ingin Menghapus Foto??',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Ya, Hapus',
                                cancelButtonText: 'Tidak',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // User confirmed deletion, proceed with the deletion logic

                                    // Hardcode the item type as 'perumahan' (change as needed)
                                    const itemType = 'perumahan';

                                    // Construct the delete URL
                                    const deleteUrl = "https://srs-ssms.com/qc_inspeksi/uploadIMG.php";

                                    // Get the filename from the image URL
                                    const imageUrlParts = imageUrl.split('/');
                                    const filename = imageUrlParts[imageUrlParts.length - 1];

                                    // Create a FormData object to send the filename, item type, and action (delete)
                                    const formData = new FormData();
                                    formData.append('filename', filename); // Send the filename to be deleted
                                    formData.append('itemType', itemType); // Send the item type to the PHP script for validation
                                    formData.append('action', 'delete'); // Specify the action as 'delete'

                                    // Send a POST request to your PHP script for deletion
                                    fetch(deleteUrl, {
                                            method: 'POST',
                                            body: formData
                                        })
                                        .then(response => response.text())
                                        .then(result => {
                                            if (result === 'Image deleted successfully.') {
                                                // Display a success message using SweetAlert
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Delete Success',
                                                    text: 'The image was deleted successfully.',
                                                });

                                                // Reload the page with cache-busting
                                                location.reload(true); // Force a hard reload (including cache)
                                            } else {
                                                // Display an error message using SweetAlert
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Delete Error',
                                                    text: 'Error: ' + result, // Display the error message from the server
                                                });
                                            }
                                        })
                                        .catch(error => {
                                            // Display an error message using SweetAlert
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Delete Error',
                                                text: 'Error: ' + error, // Display the fetch error message
                                            });
                                        });
                                }
                            });
                        });
                        // Add click event to trigger download
                        downloadLink.addEventListener("click", () => {
                            // Use the image URL to construct the download URL
                            const downloadUrl = "https://srs-ssms.com/qc_inspeksi/get_qcIMG.php?image=" + encodeURIComponent(imageUrl);

                            // Open a new tab/window to initiate the download
                            window.open(downloadUrl, "_blank");
                        });

                        uploading.addEventListener("click", () => {
                            Swal.fire({
                                title: 'Select Image',
                                input: 'file',
                                inputAttributes: {
                                    accept: 'image/*',
                                    'aria-label': 'Upload your profile picture'
                                },
                                confirmButtonText: 'Upload',
                                showCancelButton: true,
                                cancelButtonText: 'Cancel',
                                inputValidator: (value) => {
                                    if (!value) {
                                        return 'You need to select an image!';
                                    }
                                }
                            }).then((file) => {
                                if (file.value) {
                                    const selectedFile = file.value;

                                    // Get the filename from the image URL
                                    const imageUrlParts = imageUrl.split('/');
                                    const filename = imageUrlParts[imageUrlParts.length - 1];

                                    // Hardcode the item type as 'perumahan'
                                    const itemType = 'perumahan'; // Change this to 'landscape' or 'lingkungan' as needed

                                    // Construct the upload URL
                                    const uploadUrl = 'https://srs-ssms.com/qc_inspeksi/uploadIMG.php';

                                    // Create a FormData object to send the file, item type, and action (upload)
                                    const formData = new FormData();
                                    formData.append('image', selectedFile, filename); // Use the selected file with the correct filename
                                    formData.append('itemType', itemType); // Send the item type to the PHP script
                                    formData.append('action', 'upload'); // Specify the action as 'upload'

                                    // Create a new XMLHttpRequest object
                                    fetch(uploadUrl, {
                                            method: 'POST',
                                            body: formData
                                        })
                                        .then(response => response.text())
                                        .then(result => {
                                            if (result === 'File uploaded successfully.') {
                                                // Display a success message using SweetAlert
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Upload Success',
                                                    text: 'The image was uploaded successfully.',
                                                });

                                                location.reload(true);
                                            } else {
                                                // Display an error message using SweetAlert
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Upload Error',
                                                    text: 'Error: ' + result, // Display the error message from the server
                                                });
                                            }
                                        })
                                        .catch(error => {
                                            // Display an error message using SweetAlert
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Upload Error',
                                                text: 'Error: ' + error, // Display the fetch error message
                                            });
                                        });
                                }

                            });

                        });


                        editkoment.addEventListener("click", () => {
                            // Display a prompt dialog for the user to input a new comment

                            const id = data.id;
                            const komentar = data.komentar_temuan_rmh;
                            const old_koment = data.komentar_temuan_rmh;

                            let dataType = 'perumahan'
                            Swal.fire({
                                title: 'Edit Komentar',
                                input: 'text',
                                inputPlaceholder: komentar,
                                showCancelButton: true,
                                confirmButtonText: 'Save',
                                cancelButtonText: 'Cancel',
                                inputValidator: (value) => {
                                    if (!value) {
                                        return 'You need to enter a comment!';
                                    }
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    const newComment = result.value; // Get the user's input

                                    // Create an object with the parameters you want to send
                                    const params = {
                                        id: id,
                                        komen: newComment,
                                        old_koment: old_koment,
                                        dataType: dataType
                                    };
                                    var _token = $('input[name="_token"]').val();

                                    $.ajax({
                                        url: "{{ route('editkom') }}",
                                        method: "GET",
                                        data: params, // Use the params object here
                                        headers: {
                                            'X-CSRF-TOKEN': _token
                                        },
                                        success: function(result) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Berhasil',
                                                text: 'Komentar berhasil di update',
                                            });

                                            location.reload(true);
                                        },
                                        error: function(jqXHR, textStatus, errorThrown) {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Data Error',
                                                text: 'Error: ' + error, // Display the fetch error message
                                            });
                                        }
                                    });
                                }
                            });
                        });



                    }


                });


            } else {
                // If no data, show the "Perumahan not found" message
                const noDataMessage = document.createElement("p");
                noDataMessage.textContent = "Perumahan not found.";
                container.appendChild(noDataMessage);
            }
        }



        function landscapeupdate(Landscape) {
            const container = document.getElementById("landscape");
            const imageBaseUrl = "https://mobilepro.srs-ssms.com/storage/app/public/qc/landscape/";
            const defaultImageUrl = "{{ asset('img/404img.png') }}"; // Use the asset function to get the correct URL

            // Check if there is data to display
            if (Landscape.length > 0) {
                // Create the heading
                const heading = document.createElement("div");
                heading.classList.add("text-center");
                heading.innerHTML = "<h1>Foto Temuan Landscape</h1>";
                container.appendChild(heading);

                // Create the row container
                const rowContainer = document.createElement("div");
                rowContainer.classList.add("row", "justify-content-center");
                container.appendChild(rowContainer);

                // Iterate through the array data
                Landscape.forEach((item) => {
                    const id = item[0];
                    const data = item[1];
                    const imageUrl = imageBaseUrl + data.foto_temuan_ls;

                    // Create card structure
                    const card = document.createElement("div");
                    card.classList.add("col-md-6", "col-lg-3", "mb-3");

                    const cardInner = document.createElement("div");
                    cardInner.classList.add("card");

                    const image = new Image();
                    image.src = imageUrl;
                    image.alt = data.foto_temuan_ls;
                    image.classList.add("card-img-top", "img-clickable");
                    image.setAttribute("data-image", imageUrl);
                    image.setAttribute("data-comment", data.komentar_temuan_ls);
                    image.addEventListener("click", () => modalimg(imageUrl, data.komentar_temuan_ls));

                    const cardBody = document.createElement("div");
                    cardBody.classList.add("card-body");

                    const title = document.createElement("h5");
                    title.classList.add("card-title", "text-right");
                    title.textContent = `Est: ${data.title}`;

                    const text = document.createElement("p");
                    text.classList.add("card-text", "text-left");
                    text.textContent = `Temuan: ${data.komentar_temuan_ls}`;

                    // Construct card
                    cardBody.appendChild(title);
                    cardBody.appendChild(text);
                    cardInner.appendChild(image);
                    cardInner.appendChild(cardBody);
                    card.appendChild(cardInner);
                    rowContainer.appendChild(card);




                    if (currentUserName === 'Askep' || currentUserName === 'Manager' || currentUserName === 'Asisten') {
                        const buttonContainer = document.createElement("div");
                        buttonContainer.classList.add("btn-container");

                        const downloadLink = document.createElement("a");
                        downloadLink.href = "#"; // Set a placeholder link initially
                        downloadLink.innerHTML = '<i class="fas fa-download"></i> Download Image';
                        downloadLink.classList.add("btn", "btn-primary", "btn-sm");
                        buttonContainer.appendChild(downloadLink);

                        const uploading = document.createElement("a");
                        uploading.href = "#"; // Set a placeholder link initially
                        uploading.innerHTML = '<i class="fa fa-cloud-upload" aria-hidden="true"></i> Upload Image';
                        uploading.classList.add("btn", "btn-primary", "btn-sm");
                        buttonContainer.appendChild(uploading);

                        const deletes = document.createElement("a");
                        deletes.href = "#"; // Set a placeholder link initially
                        deletes.innerHTML = '<i class="fa fa-trash" aria-hidden="true"></i> Delete The Image';
                        deletes.classList.add("btn", "btn-primary", "btn-sm");
                        buttonContainer.appendChild(deletes);

                        const editkoment = document.createElement("a");
                        editkoment.href = "#"; // Set a placeholder link initially
                        editkoment.innerHTML = '<i class="fa fa-comments" aria-hidden="true"></i> Edit Komentar';
                        editkoment.classList.add("btn", "btn-primary", "btn-sm");
                        buttonContainer.appendChild(editkoment);
                        // Append the container to the card body
                        card.querySelector(".card-body").appendChild(buttonContainer);



                        deletes.addEventListener("click", () => {
                            // Display a confirmation dialog
                            Swal.fire({
                                title: 'Delete Confirmation',
                                text: 'Anda Yaking Ingin Menghapus Foto??',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Ya, Hapus',
                                cancelButtonText: 'Tidak',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // User confirmed deletion, proceed with the deletion logic

                                    // Hardcode the item type as 'perumahan' (change as needed)
                                    const itemType = 'landscape';

                                    // Construct the delete URL
                                    const deleteUrl = "https://srs-ssms.com/qc_inspeksi/uploadIMG.php";

                                    // Get the filename from the image URL
                                    const imageUrlParts = imageUrl.split('/');
                                    const filename = imageUrlParts[imageUrlParts.length - 1];

                                    // Create a FormData object to send the filename, item type, and action (delete)
                                    const formData = new FormData();
                                    formData.append('filename', filename); // Send the filename to be deleted
                                    formData.append('itemType', itemType); // Send the item type to the PHP script for validation
                                    formData.append('action', 'delete'); // Specify the action as 'delete'

                                    // Send a POST request to your PHP script for deletion
                                    fetch(deleteUrl, {
                                            method: 'POST',
                                            body: formData
                                        })
                                        .then(response => response.text())
                                        .then(result => {
                                            if (result === 'Image deleted successfully.') {
                                                // Display a success message using SweetAlert
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Delete Success',
                                                    text: 'The image was deleted successfully.',
                                                });

                                                // Reload the page with cache-busting
                                                location.reload(true); // Force a hard reload (including cache)
                                            } else {
                                                // Display an error message using SweetAlert
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Delete Error',
                                                    text: 'Error: ' + result, // Display the error message from the server
                                                });
                                            }
                                        })
                                        .catch(error => {
                                            // Display an error message using SweetAlert
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Delete Error',
                                                text: 'Error: ' + error, // Display the fetch error message
                                            });
                                        });
                                }
                            });
                        });
                        // Add click event to trigger download
                        downloadLink.addEventListener("click", () => {
                            // Use the image URL to construct the download URL
                            const downloadUrl = "https://srs-ssms.com/qc_inspeksi/get_qcIMG.php?image=" + encodeURIComponent(imageUrl);

                            // Open a new tab/window to initiate the download
                            window.open(downloadUrl, "_blank");
                        });

                        uploading.addEventListener("click", () => {
                            Swal.fire({
                                title: 'Select Image',
                                input: 'file',
                                inputAttributes: {
                                    accept: 'image/*',
                                    'aria-label': 'Upload your profile picture'
                                },
                                confirmButtonText: 'Upload',
                                showCancelButton: true,
                                cancelButtonText: 'Cancel',
                                inputValidator: (value) => {
                                    if (!value) {
                                        return 'You need to select an image!';
                                    }
                                }
                            }).then((file) => {
                                if (file.value) {
                                    const selectedFile = file.value;

                                    // Get the filename from the image URL
                                    const imageUrlParts = imageUrl.split('/');
                                    const filename = imageUrlParts[imageUrlParts.length - 1];

                                    // Hardcode the item type as 'perumahan'
                                    const itemType = 'landscape'; // Change this to 'landscape' or 'lingkungan' as needed

                                    // Construct the upload URL
                                    const uploadUrl = 'https://srs-ssms.com/qc_inspeksi/uploadIMG.php';

                                    // Create a FormData object to send the file, item type, and action (upload)
                                    const formData = new FormData();
                                    formData.append('image', selectedFile, filename); // Use the selected file with the correct filename
                                    formData.append('itemType', itemType); // Send the item type to the PHP script
                                    formData.append('action', 'upload'); // Specify the action as 'upload'

                                    // Create a new XMLHttpRequest object
                                    fetch(uploadUrl, {
                                            method: 'POST',
                                            body: formData
                                        })
                                        .then(response => response.text())
                                        .then(result => {
                                            if (result === 'File uploaded successfully.') {
                                                // Display a success message using SweetAlert
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Upload Success',
                                                    text: 'The image was uploaded successfully.',
                                                });

                                                location.reload(true);
                                            } else {
                                                // Display an error message using SweetAlert
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Upload Error',
                                                    text: 'Error: ' + result, // Display the error message from the server
                                                });
                                            }
                                        })
                                        .catch(error => {
                                            // Display an error message using SweetAlert
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Upload Error',
                                                text: 'Error: ' + error, // Display the fetch error message
                                            });
                                        });
                                }

                            });

                        });


                        editkoment.addEventListener("click", () => {
                            // Display a prompt dialog for the user to input a new comment

                            const id = data.id;
                            const komentar = data.komentar_temuan_ls;
                            const old_koment = data.komentar_temuan_ls;

                            let dataType = 'landscape'
                            Swal.fire({
                                title: 'Edit Komentar',
                                input: 'text',
                                inputPlaceholder: komentar,
                                showCancelButton: true,
                                confirmButtonText: 'Save',
                                cancelButtonText: 'Cancel',
                                inputValidator: (value) => {
                                    if (!value) {
                                        return 'You need to enter a comment!';
                                    }
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    const newComment = result.value; // Get the user's input

                                    // Create an object with the parameters you want to send
                                    const params = {
                                        id: id,
                                        komen: newComment,
                                        old_koment: old_koment,
                                        dataType: dataType
                                    };
                                    var _token = $('input[name="_token"]').val();

                                    $.ajax({
                                        url: "{{ route('editkom') }}",
                                        method: "GET",
                                        data: params, // Use the params object here
                                        headers: {
                                            'X-CSRF-TOKEN': _token
                                        },
                                        success: function(result) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Berhasil',
                                                text: 'Komentar berhasil di update',
                                            });

                                            location.reload(true);
                                        },
                                        error: function(jqXHR, textStatus, errorThrown) {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Data Error',
                                                text: 'Error: ' + error, // Display the fetch error message
                                            });
                                        }
                                    });
                                }
                            });
                        });



                    }
                });
            } else {
                // If no data, show the "Perumahan not found" message
                const noDataMessage = document.createElement("p");
                noDataMessage.textContent = "Landscape not found.";
                container.appendChild(noDataMessage);
            }
        }

        function lingkunganupdate(lingkungan) {
            const container = document.getElementById("lingkungan");
            const imageBaseUrl = "https://mobilepro.srs-ssms.com/storage/app/public/qc/lingkungan/";
            const defaultImageUrl = "{{ asset('img/404img.png') }}"; // Use the asset function to get the correct URL

            // Check if there is data to display
            if (lingkungan.length > 0) {
                // Create the heading
                const heading = document.createElement("div");
                heading.classList.add("text-center");
                heading.innerHTML = "<h1>Foto Temuan Lingkungan</h1>";
                container.appendChild(heading);

                // Create the row container
                const rowContainer = document.createElement("div");
                rowContainer.classList.add("row", "justify-content-center");
                container.appendChild(rowContainer);

                // Iterate through the array data
                lingkungan.forEach((item) => {
                    const id = item[0];
                    const data = item[1];
                    const imageUrl = imageBaseUrl + data.foto_temuan_ll;

                    // Create card structure
                    const card = document.createElement("div");
                    card.classList.add("col-md-6", "col-lg-3", "mb-3");

                    const cardInner = document.createElement("div");
                    cardInner.classList.add("card");

                    const image = new Image();
                    image.src = imageUrl;
                    image.alt = data.foto_temuan_ll;
                    image.classList.add("card-img-top", "img-clickable");
                    image.setAttribute("data-image", imageUrl);
                    image.setAttribute("data-comment", data.komentar_temuan_ll);
                    image.addEventListener("click", () => modalimg(imageUrl, data.komentar_temuan_ll));

                    const cardBody = document.createElement("div");
                    cardBody.classList.add("card-body");

                    const title = document.createElement("h5");
                    title.classList.add("card-title", "text-right");
                    title.textContent = `Est: ${data.title}`;

                    const text = document.createElement("p");
                    text.classList.add("card-text", "text-left");
                    text.textContent = `Temuan: ${data.komentar_temuan_ll}`;

                    // Construct card
                    cardBody.appendChild(title);
                    cardBody.appendChild(text);
                    cardInner.appendChild(image);
                    cardInner.appendChild(cardBody);
                    card.appendChild(cardInner);


                    rowContainer.appendChild(card);

                    if (currentUserName === 'Askep' || currentUserName === 'Manager' || currentUserName === 'Asisten') {
                        const buttonContainer = document.createElement("div");
                        buttonContainer.classList.add("btn-container");

                        const downloadLink = document.createElement("a");
                        downloadLink.href = "#"; // Set a placeholder link initially
                        downloadLink.innerHTML = '<i class="fas fa-download"></i> Download Image';
                        downloadLink.classList.add("btn", "btn-primary", "btn-sm");
                        buttonContainer.appendChild(downloadLink);

                        const uploading = document.createElement("a");
                        uploading.href = "#"; // Set a placeholder link initially
                        uploading.innerHTML = '<i class="fa fa-cloud-upload" aria-hidden="true"></i> Upload Image';
                        uploading.classList.add("btn", "btn-primary", "btn-sm");
                        buttonContainer.appendChild(uploading);

                        const deletes = document.createElement("a");
                        deletes.href = "#"; // Set a placeholder link initially
                        deletes.innerHTML = '<i class="fa fa-trash" aria-hidden="true"></i> Delete The Image';
                        deletes.classList.add("btn", "btn-primary", "btn-sm");
                        buttonContainer.appendChild(deletes);

                        const editkoment = document.createElement("a");
                        editkoment.href = "#"; // Set a placeholder link initially
                        editkoment.innerHTML = '<i class="fa fa-comments" aria-hidden="true"></i> Edit Komentar';
                        editkoment.classList.add("btn", "btn-primary", "btn-sm");
                        buttonContainer.appendChild(editkoment);
                        // Append the container to the card body
                        card.querySelector(".card-body").appendChild(buttonContainer);



                        deletes.addEventListener("click", () => {
                            // Display a confirmation dialog
                            Swal.fire({
                                title: 'Delete Confirmation',
                                text: 'Anda Yaking Ingin Menghapus Foto??',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Ya, Hapus',
                                cancelButtonText: 'Tidak',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // User confirmed deletion, proceed with the deletion logic

                                    // Hardcode the item type as 'perumahan' (change as needed)
                                    const itemType = 'lingkungan';

                                    // Construct the delete URL
                                    const deleteUrl = "https://srs-ssms.com/qc_inspeksi/uploadIMG.php";

                                    // Get the filename from the image URL
                                    const imageUrlParts = imageUrl.split('/');
                                    const filename = imageUrlParts[imageUrlParts.length - 1];

                                    // Create a FormData object to send the filename, item type, and action (delete)
                                    const formData = new FormData();
                                    formData.append('filename', filename); // Send the filename to be deleted
                                    formData.append('itemType', itemType); // Send the item type to the PHP script for validation
                                    formData.append('action', 'delete'); // Specify the action as 'delete'

                                    // Send a POST request to your PHP script for deletion
                                    fetch(deleteUrl, {
                                            method: 'POST',
                                            body: formData
                                        })
                                        .then(response => response.text())
                                        .then(result => {
                                            if (result === 'Image deleted successfully.') {
                                                // Display a success message using SweetAlert
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Delete Success',
                                                    text: 'The image was deleted successfully.',
                                                });

                                                // Reload the page with cache-busting
                                                location.reload(true); // Force a hard reload (including cache)
                                            } else {
                                                // Display an error message using SweetAlert
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Delete Error',
                                                    text: 'Error: ' + result, // Display the error message from the server
                                                });
                                            }
                                        })
                                        .catch(error => {
                                            // Display an error message using SweetAlert
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Delete Error',
                                                text: 'Error: ' + error, // Display the fetch error message
                                            });
                                        });
                                }
                            });
                        });
                        // Add click event to trigger download
                        downloadLink.addEventListener("click", () => {
                            // Use the image URL to construct the download URL
                            const downloadUrl = "https://srs-ssms.com/qc_inspeksi/get_qcIMG.php?image=" + encodeURIComponent(imageUrl);

                            // Open a new tab/window to initiate the download
                            window.open(downloadUrl, "_blank");
                        });

                        uploading.addEventListener("click", () => {
                            Swal.fire({
                                title: 'Select Image',
                                input: 'file',
                                inputAttributes: {
                                    accept: 'image/*',
                                    'aria-label': 'Upload your profile picture'
                                },
                                confirmButtonText: 'Upload',
                                showCancelButton: true,
                                cancelButtonText: 'Cancel',
                                inputValidator: (value) => {
                                    if (!value) {
                                        return 'You need to select an image!';
                                    }
                                }
                            }).then((file) => {
                                if (file.value) {
                                    const selectedFile = file.value;

                                    // Get the filename from the image URL
                                    const imageUrlParts = imageUrl.split('/');
                                    const filename = imageUrlParts[imageUrlParts.length - 1];

                                    // Hardcode the item type as 'perumahan'
                                    const itemType = 'lingkungan'; // Change this to 'landscape' or 'lingkungan' as needed

                                    // Construct the upload URL
                                    const uploadUrl = 'https://srs-ssms.com/qc_inspeksi/uploadIMG.php';

                                    // Create a FormData object to send the file, item type, and action (upload)
                                    const formData = new FormData();
                                    formData.append('image', selectedFile, filename); // Use the selected file with the correct filename
                                    formData.append('itemType', itemType); // Send the item type to the PHP script
                                    formData.append('action', 'upload'); // Specify the action as 'upload'

                                    // Create a new XMLHttpRequest object
                                    fetch(uploadUrl, {
                                            method: 'POST',
                                            body: formData
                                        })
                                        .then(response => response.text())
                                        .then(result => {
                                            if (result === 'File uploaded successfully.') {
                                                // Display a success message using SweetAlert
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Upload Success',
                                                    text: 'The image was uploaded successfully.',
                                                });

                                                location.reload(true);
                                            } else {
                                                // Display an error message using SweetAlert
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Upload Error',
                                                    text: 'Error: ' + result, // Display the error message from the server
                                                });
                                            }
                                        })
                                        .catch(error => {
                                            // Display an error message using SweetAlert
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Upload Error',
                                                text: 'Error: ' + error, // Display the fetch error message
                                            });
                                        });
                                }

                            });

                        });


                        editkoment.addEventListener("click", () => {
                            // Display a prompt dialog for the user to input a new comment

                            const id = data.id;
                            const komentar = data.komentar_temuan_ll;
                            const old_koment = data.komentar_temuan_ll;

                            let dataType = 'lingkungan'
                            Swal.fire({
                                title: 'Edit Komentar',
                                input: 'text',
                                inputPlaceholder: komentar,
                                showCancelButton: true,
                                confirmButtonText: 'Save',
                                cancelButtonText: 'Cancel',
                                inputValidator: (value) => {
                                    if (!value) {
                                        return 'You need to enter a comment!';
                                    }
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    const newComment = result.value; // Get the user's input

                                    // Create an object with the parameters you want to send
                                    const params = {
                                        id: id,
                                        komen: newComment,
                                        old_koment: old_koment,
                                        dataType: dataType
                                    };
                                    var _token = $('input[name="_token"]').val();

                                    $.ajax({
                                        url: "{{ route('editkom') }}",
                                        method: "GET",
                                        data: params, // Use the params object here
                                        headers: {
                                            'X-CSRF-TOKEN': _token
                                        },
                                        success: function(result) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Berhasil',
                                                text: 'Komentar berhasil di update',
                                            });

                                            location.reload(true);
                                        },
                                        error: function(jqXHR, textStatus, errorThrown) {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Data Error',
                                                text: 'Error: ' + error, // Display the fetch error message
                                            });
                                        }
                                    });
                                }
                            });
                        });


                    }
                });
            } else {
                // If no data, show the "Perumahan not found" message
                const noDataMessage = document.createElement("p");
                noDataMessage.textContent = "Lingkungan not found.";
                container.appendChild(noDataMessage);
            }
        }


        function afd_rmh(rumah_afd) {
            const container = document.getElementById("afd_rmh");
            const imageBaseUrl = "https://mobilepro.srs-ssms.com/storage/app/public/qc/perumahan/";
            const defaultImageUrl = "{{ asset('img/404img.png') }}"; // Use the asset function to get the correct URL
            // console.log(rumah_afd);
            // console.log(rumah_afd);
            // Check if there is data to display
            if (rumah_afd.length > 0) {
                // Create the heading
                const heading = document.createElement("div");
                heading.classList.add("text-center");
                heading.innerHTML = "<h1>Foto Temuan Perumahan</h1>";
                container.appendChild(heading);

                // Create the row container
                const rowContainer = document.createElement("div");
                rowContainer.classList.add("row", "justify-content-center");
                container.appendChild(rowContainer);

                // Iterate through the array data
                rumah_afd.forEach((item) => {
                    const id = item[0];
                    const data = item[1];
                    const imageUrl = imageBaseUrl + data.foto_temuan_rmh;


                    // Create card structure
                    const card = document.createElement("div");
                    card.classList.add("col-md-6", "col-lg-3", "mb-3");

                    const cardInner = document.createElement("div");
                    cardInner.classList.add("card");

                    const image = new Image();
                    image.src = imageUrl;
                    image.alt = data.foto_temuan_rmh;
                    image.classList.add("card-img-top", "img-clickable");
                    image.setAttribute("data-image", imageUrl);
                    image.setAttribute("data-comment", data.komentar_temuan_rmh);
                    image.addEventListener("click", () => modalimg(imageUrl, data.komentar_temuan_rmh));

                    const cardBody = document.createElement("div");
                    cardBody.classList.add("card-body");

                    const title = document.createElement("h5");
                    title.classList.add("card-title", "text-right");
                    title.textContent = `Est: ${data.title}`;

                    const text = document.createElement("p");
                    text.classList.add("card-text", "text-left");
                    text.textContent = `Temuan: ${data.komentar_temuan_rmh}`;

                    // Construct card
                    cardBody.appendChild(title);
                    cardBody.appendChild(text);
                    cardInner.appendChild(image);
                    cardInner.appendChild(cardBody);
                    card.appendChild(cardInner);
                    rowContainer.appendChild(card);

                    if (currentUserName === 'Askep' || currentUserName === 'Manager' || currentUserName === 'Asisten') {
                        const buttonContainer = document.createElement("div");
                        buttonContainer.classList.add("btn-container");

                        const downloadLink = document.createElement("a");
                        downloadLink.href = "#"; // Set a placeholder link initially
                        downloadLink.innerHTML = '<i class="fas fa-download"></i> Download';
                        downloadLink.classList.add("btn", "btn-primary", "btn-sm");
                        buttonContainer.appendChild(downloadLink);

                        const uploading = document.createElement("a");
                        uploading.href = "#"; // Set a placeholder link initially
                        uploading.innerHTML = '<i class="fa fa-cloud-upload" aria-hidden="true"></i> Upload ';
                        uploading.classList.add("btn", "btn-primary", "btn-sm");
                        buttonContainer.appendChild(uploading);

                        const deletes = document.createElement("a");
                        deletes.href = "#"; // Set a placeholder link initially
                        deletes.innerHTML = '<i class="fa fa-trash" aria-hidden="true"></i> Hapus';
                        deletes.classList.add("btn", "btn-primary", "btn-sm");
                        buttonContainer.appendChild(deletes);


                        const editkoment = document.createElement("a");
                        editkoment.href = "#"; // Set a placeholder link initially
                        editkoment.innerHTML = '<i class="fa fa-comments" aria-hidden="true"></i> Edit Komentar';
                        editkoment.classList.add("btn", "btn-primary", "btn-sm");
                        buttonContainer.appendChild(editkoment);

                        // Append the container to the card body
                        card.querySelector(".card-body").appendChild(buttonContainer);



                        deletes.addEventListener("click", () => {
                            // Display a confirmation dialog
                            Swal.fire({
                                title: 'Delete Confirmation',
                                text: 'Anda Yaking Ingin Menghapus Foto??',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Ya, Hapus',
                                cancelButtonText: 'Tidak',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // User confirmed deletion, proceed with the deletion logic

                                    // Hardcode the item type as 'perumahan' (change as needed)
                                    const itemType = 'perumahan';

                                    // Construct the delete URL
                                    const deleteUrl = "https://srs-ssms.com/qc_inspeksi/uploadIMG.php";

                                    // Get the filename from the image URL
                                    const imageUrlParts = imageUrl.split('/');
                                    const filename = imageUrlParts[imageUrlParts.length - 1];

                                    // Create a FormData object to send the filename, item type, and action (delete)
                                    const formData = new FormData();
                                    formData.append('filename', filename); // Send the filename to be deleted
                                    formData.append('itemType', itemType); // Send the item type to the PHP script for validation
                                    formData.append('action', 'delete'); // Specify the action as 'delete'

                                    // Send a POST request to your PHP script for deletion
                                    fetch(deleteUrl, {
                                            method: 'POST',
                                            body: formData
                                        })
                                        .then(response => response.text())
                                        .then(result => {
                                            if (result === 'Image deleted successfully.') {
                                                // Display a success message using SweetAlert
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Delete Success',
                                                    text: 'The image was deleted successfully.',
                                                });

                                                // Reload the page with cache-busting
                                                location.reload(true); // Force a hard reload (including cache)
                                            } else {
                                                // Display an error message using SweetAlert
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Delete Error',
                                                    text: 'Error: ' + result, // Display the error message from the server
                                                });
                                            }
                                        })
                                        .catch(error => {
                                            // Display an error message using SweetAlert
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Delete Error',
                                                text: 'Error: ' + error, // Display the fetch error message
                                            });
                                        });
                                }
                            });
                        });
                        // Add click event to trigger download
                        downloadLink.addEventListener("click", () => {
                            // Use the image URL to construct the download URL
                            const downloadUrl = "https://srs-ssms.com/qc_inspeksi/get_qcIMG.php?image=" + encodeURIComponent(imageUrl);

                            // Open a new tab/window to initiate the download
                            window.open(downloadUrl, "_blank");
                        });

                        uploading.addEventListener("click", () => {
                            Swal.fire({
                                title: 'Select Image',
                                input: 'file',
                                inputAttributes: {
                                    accept: 'image/*',
                                    'aria-label': 'Upload your profile picture'
                                },
                                confirmButtonText: 'Upload',
                                showCancelButton: true,
                                cancelButtonText: 'Cancel',
                                inputValidator: (value) => {
                                    if (!value) {
                                        return 'You need to select an image!';
                                    }
                                }
                            }).then((file) => {
                                if (file.value) {
                                    const selectedFile = file.value;

                                    // Get the filename from the image URL
                                    const imageUrlParts = imageUrl.split('/');
                                    const filename = imageUrlParts[imageUrlParts.length - 1];

                                    // Hardcode the item type as 'perumahan'
                                    const itemType = 'perumahan'; // Change this to 'landscape' or 'lingkungan' as needed

                                    // Construct the upload URL
                                    const uploadUrl = 'https://srs-ssms.com/qc_inspeksi/uploadIMG.php';

                                    // Create a FormData object to send the file, item type, and action (upload)
                                    const formData = new FormData();
                                    formData.append('image', selectedFile, filename); // Use the selected file with the correct filename
                                    formData.append('itemType', itemType); // Send the item type to the PHP script
                                    formData.append('action', 'upload'); // Specify the action as 'upload'

                                    // Create a new XMLHttpRequest object
                                    fetch(uploadUrl, {
                                            method: 'POST',
                                            body: formData
                                        })
                                        .then(response => response.text())
                                        .then(result => {
                                            if (result === 'File uploaded successfully.') {
                                                // Display a success message using SweetAlert
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Upload Success',
                                                    text: 'The image was uploaded successfully.',
                                                });

                                                location.reload(true);
                                            } else {
                                                // Display an error message using SweetAlert
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Upload Error',
                                                    text: 'Error: ' + result, // Display the error message from the server
                                                });
                                            }
                                        })
                                        .catch(error => {
                                            // Display an error message using SweetAlert
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Upload Error',
                                                text: 'Error: ' + error, // Display the fetch error message
                                            });
                                        });
                                }

                            });

                        });

                        editkoment.addEventListener("click", () => {
                            // Display a prompt dialog for the user to input a new comment

                            const id = data.id;
                            const komentar = data.komentar_temuan_rmh;
                            const old_koment = data.komentar_temuan_rmh;

                            let dataType = 'perumahan_afd'
                            Swal.fire({
                                title: 'Edit Komentar',
                                input: 'text',
                                inputPlaceholder: komentar,
                                showCancelButton: true,
                                confirmButtonText: 'Save',
                                cancelButtonText: 'Cancel',
                                inputValidator: (value) => {
                                    if (!value) {
                                        return 'You need to enter a comment!';
                                    }
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    const newComment = result.value; // Get the user's input

                                    // Create an object with the parameters you want to send
                                    const params = {
                                        id: id,
                                        komen: newComment,
                                        old_koment: old_koment,
                                        dataType: dataType
                                    };
                                    var _token = $('input[name="_token"]').val();

                                    $.ajax({
                                        url: "{{ route('editkom') }}",
                                        method: "GET",
                                        data: params, // Use the params object here
                                        headers: {
                                            'X-CSRF-TOKEN': _token
                                        },
                                        success: function(result) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Berhasil',
                                                text: 'Komentar berhasil di update',
                                            });

                                            location.reload(true);
                                        },
                                        error: function(jqXHR, textStatus, errorThrown) {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Data Error',
                                                text: 'Error: ' + error, // Display the fetch error message
                                            });
                                        }
                                    });
                                }
                            });
                        });



                    }
                    // Create a container div for the buttons


                });
            } else {
                // If no data, show the "Perumahan not found" message
                const noDataMessage = document.createElement("p");
                noDataMessage.textContent = "Perumahan not found.";
                container.appendChild(noDataMessage);
            }
        }

        function afd_landscape(lcp_afd) {
            const container = document.getElementById("afd_landscape");
            const imageBaseUrl = "https://mobilepro.srs-ssms.com/storage/app/public/qc/landscape/";
            const defaultImageUrl = "{{ asset('img/404img.png') }}"; // Use the asset function to get the correct URL

            // Check if there is data to display
            if (lcp_afd.length > 0) {
                // Create the heading
                const heading = document.createElement("div");
                heading.classList.add("text-center");
                heading.innerHTML = "<h1>Foto Temuan Landscape</h1>";
                container.appendChild(heading);

                // Create the row container
                const rowContainer = document.createElement("div");
                rowContainer.classList.add("row", "justify-content-center");
                container.appendChild(rowContainer);

                // Iterate through the array data
                lcp_afd.forEach((item) => {
                    const id = item[0];
                    const data = item[1];
                    const imageUrl = imageBaseUrl + data.foto_temuan_lcp;

                    // Create card structure
                    const card = document.createElement("div");
                    card.classList.add("col-md-6", "col-lg-3", "mb-3");

                    const cardInner = document.createElement("div");
                    cardInner.classList.add("card");

                    const image = new Image();
                    image.src = imageUrl;
                    image.alt = data.foto_temuan_lcp;
                    image.classList.add("card-img-top", "img-clickable");
                    image.setAttribute("data-image", imageUrl);
                    image.setAttribute("data-comment", data.komentar_temuan_lcp);
                    image.addEventListener("click", () => modalimg(imageUrl, data.komentar_temuan_lcp));

                    const cardBody = document.createElement("div");
                    cardBody.classList.add("card-body");

                    const title = document.createElement("h5");
                    title.classList.add("card-title", "text-right");
                    title.textContent = `Est: ${data.title}`;

                    const text = document.createElement("p");
                    text.classList.add("card-text", "text-left");
                    text.textContent = `Temuan: ${data.komentar_temuan_lcp}`;

                    // Construct card
                    cardBody.appendChild(title);
                    cardBody.appendChild(text);
                    cardInner.appendChild(image);
                    cardInner.appendChild(cardBody);
                    card.appendChild(cardInner);
                    rowContainer.appendChild(card);
                    if (currentUserName === 'Askep' || currentUserName === 'Manager' || currentUserName === 'Asisten') {
                        const buttonContainer = document.createElement("div");
                        buttonContainer.classList.add("btn-container");

                        const downloadLink = document.createElement("a");
                        downloadLink.href = "#"; // Set a placeholder link initially
                        downloadLink.innerHTML = '<i class="fas fa-download"></i> Download Image';
                        downloadLink.classList.add("btn", "btn-primary", "btn-sm");
                        buttonContainer.appendChild(downloadLink);

                        const uploading = document.createElement("a");
                        uploading.href = "#"; // Set a placeholder link initially
                        uploading.innerHTML = '<i class="fa fa-cloud-upload" aria-hidden="true"></i> Upload Image';
                        uploading.classList.add("btn", "btn-primary", "btn-sm");
                        buttonContainer.appendChild(uploading);

                        const deletes = document.createElement("a");
                        deletes.href = "#"; // Set a placeholder link initially
                        deletes.innerHTML = '<i class="fa fa-trash" aria-hidden="true"></i> Delete The Image';
                        deletes.classList.add("btn", "btn-primary", "btn-sm");
                        buttonContainer.appendChild(deletes);

                        const editkoment = document.createElement("a");
                        editkoment.href = "#"; // Set a placeholder link initially
                        editkoment.innerHTML = '<i class="fa fa-comments" aria-hidden="true"></i> Edit Komentar';
                        editkoment.classList.add("btn", "btn-primary", "btn-sm");
                        buttonContainer.appendChild(editkoment);
                        // Append the container to the card body
                        card.querySelector(".card-body").appendChild(buttonContainer);



                        deletes.addEventListener("click", () => {
                            // Display a confirmation dialog
                            Swal.fire({
                                title: 'Delete Confirmation',
                                text: 'Anda Yaking Ingin Menghapus Foto??',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Ya, Hapus',
                                cancelButtonText: 'Tidak',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // User confirmed deletion, proceed with the deletion logic

                                    // Hardcode the item type as 'perumahan' (change as needed)
                                    const itemType = 'landscape';

                                    // Construct the delete URL
                                    const deleteUrl = "https://srs-ssms.com/qc_inspeksi/uploadIMG.php";

                                    // Get the filename from the image URL
                                    const imageUrlParts = imageUrl.split('/');
                                    const filename = imageUrlParts[imageUrlParts.length - 1];

                                    // Create a FormData object to send the filename, item type, and action (delete)
                                    const formData = new FormData();
                                    formData.append('filename', filename); // Send the filename to be deleted
                                    formData.append('itemType', itemType); // Send the item type to the PHP script for validation
                                    formData.append('action', 'delete'); // Specify the action as 'delete'

                                    // Send a POST request to your PHP script for deletion
                                    fetch(deleteUrl, {
                                            method: 'POST',
                                            body: formData
                                        })
                                        .then(response => response.text())
                                        .then(result => {
                                            if (result === 'Image deleted successfully.') {
                                                // Display a success message using SweetAlert
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Delete Success',
                                                    text: 'The image was deleted successfully.',
                                                });

                                                // Reload the page with cache-busting
                                                location.reload(true); // Force a hard reload (including cache)
                                            } else {
                                                // Display an error message using SweetAlert
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Delete Error',
                                                    text: 'Error: ' + result, // Display the error message from the server
                                                });
                                            }
                                        })
                                        .catch(error => {
                                            // Display an error message using SweetAlert
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Delete Error',
                                                text: 'Error: ' + error, // Display the fetch error message
                                            });
                                        });
                                }
                            });
                        });
                        // Add click event to trigger download
                        downloadLink.addEventListener("click", () => {
                            // Use the image URL to construct the download URL
                            const downloadUrl = "https://srs-ssms.com/qc_inspeksi/get_qcIMG.php?image=" + encodeURIComponent(imageUrl);

                            // Open a new tab/window to initiate the download
                            window.open(downloadUrl, "_blank");
                        });

                        uploading.addEventListener("click", () => {
                            Swal.fire({
                                title: 'Select Image',
                                input: 'file',
                                inputAttributes: {
                                    accept: 'image/*',
                                    'aria-label': 'Upload your profile picture'
                                },
                                confirmButtonText: 'Upload',
                                showCancelButton: true,
                                cancelButtonText: 'Cancel',
                                inputValidator: (value) => {
                                    if (!value) {
                                        return 'You need to select an image!';
                                    }
                                }
                            }).then((file) => {
                                if (file.value) {
                                    const selectedFile = file.value;

                                    // Get the filename from the image URL
                                    const imageUrlParts = imageUrl.split('/');
                                    const filename = imageUrlParts[imageUrlParts.length - 1];

                                    // Hardcode the item type as 'perumahan'
                                    const itemType = 'landscape'; // Change this to 'landscape' or 'lingkungan' as needed

                                    // Construct the upload URL
                                    const uploadUrl = 'https://srs-ssms.com/qc_inspeksi/uploadIMG.php';

                                    // Create a FormData object to send the file, item type, and action (upload)
                                    const formData = new FormData();
                                    formData.append('image', selectedFile, filename); // Use the selected file with the correct filename
                                    formData.append('itemType', itemType); // Send the item type to the PHP script
                                    formData.append('action', 'upload'); // Specify the action as 'upload'

                                    // Create a new XMLHttpRequest object
                                    fetch(uploadUrl, {
                                            method: 'POST',
                                            body: formData
                                        })
                                        .then(response => response.text())
                                        .then(result => {
                                            if (result === 'File uploaded successfully.') {
                                                // Display a success message using SweetAlert
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Upload Success',
                                                    text: 'The image was uploaded successfully.',
                                                });

                                                location.reload(true);
                                            } else {
                                                // Display an error message using SweetAlert
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Upload Error',
                                                    text: 'Error: ' + result, // Display the error message from the server
                                                });
                                            }
                                        })
                                        .catch(error => {
                                            // Display an error message using SweetAlert
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Upload Error',
                                                text: 'Error: ' + error, // Display the fetch error message
                                            });
                                        });
                                }

                            });

                        });


                        editkoment.addEventListener("click", () => {
                            // Display a prompt dialog for the user to input a new comment

                            const id = data.id;
                            const komentar = data.komentar_temuan_lcp;
                            const old_koment = data.komentar_temuan_lcp;

                            let dataType = 'landscape_afd'
                            Swal.fire({
                                title: 'Edit Komentar',
                                input: 'text',
                                inputPlaceholder: komentar,
                                showCancelButton: true,
                                confirmButtonText: 'Save',
                                cancelButtonText: 'Cancel',
                                inputValidator: (value) => {
                                    if (!value) {
                                        return 'You need to enter a comment!';
                                    }
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    const newComment = result.value; // Get the user's input

                                    // Create an object with the parameters you want to send
                                    const params = {
                                        id: id,
                                        komen: newComment,
                                        old_koment: old_koment,
                                        dataType: dataType
                                    };
                                    var _token = $('input[name="_token"]').val();

                                    $.ajax({
                                        url: "{{ route('editkom') }}",
                                        method: "GET",
                                        data: params, // Use the params object here
                                        headers: {
                                            'X-CSRF-TOKEN': _token
                                        },
                                        success: function(result) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Berhasil',
                                                text: 'Komentar berhasil di update',
                                            });

                                            location.reload(true);
                                        },
                                        error: function(jqXHR, textStatus, errorThrown) {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Data Error',
                                                text: 'Error: ' + error, // Display the fetch error message
                                            });
                                        }
                                    });
                                }
                            });
                        });



                    }
                });
            } else {
                // If no data, show the "Perumahan not found" message
                const noDataMessage = document.createElement("p");
                noDataMessage.textContent = "Landscape not found.";
                container.appendChild(noDataMessage);
            }
        }

        function afd_lingkungan(lingkungan_afd) {
            const container = document.getElementById("afd_lingkungan");
            const imageBaseUrl = "https://mobilepro.srs-ssms.com/storage/app/public/qc/lingkungan/";
            const defaultImageUrl = "{{ asset('img/404img.png') }}"; // Use the asset function to get the correct URL

            // Check if there is data to display
            if (lingkungan_afd.length > 0) {
                // Create the heading
                const heading = document.createElement("div");
                heading.classList.add("text-center");
                heading.innerHTML = "<h1>Foto Temuan Lingkungan</h1>";
                container.appendChild(heading);

                // Create the row container
                const rowContainer = document.createElement("div");
                rowContainer.classList.add("row", "justify-content-center");
                container.appendChild(rowContainer);

                // Iterate through the array data
                lingkungan_afd.forEach((item) => {
                    const id = item[0];
                    const data = item[1];
                    const imageUrl = imageBaseUrl + data.foto_temuan_lk;


                    // Create card structure
                    const card = document.createElement("div");
                    card.classList.add("col-md-6", "col-lg-3", "mb-3");

                    const cardInner = document.createElement("div");
                    cardInner.classList.add("card");

                    const image = new Image();
                    image.src = imageUrl;
                    image.alt = data.foto_temuan_lk;
                    image.classList.add("card-img-top", "img-clickable");
                    image.setAttribute("data-image", imageUrl);
                    image.setAttribute("data-comment", data.komentar_temuan_lk);
                    image.addEventListener("click", () => modalimg(imageUrl, data.komentar_temuan_lk));

                    const cardBody = document.createElement("div");
                    cardBody.classList.add("card-body");

                    const title = document.createElement("h5");
                    title.classList.add("card-title", "text-right");
                    title.textContent = `Est: ${data.title}`;

                    const text = document.createElement("p");
                    text.classList.add("card-text", "text-left");
                    text.textContent = `Temuan: ${data.komentar_temuan_lk}`;

                    // Construct card
                    cardBody.appendChild(title);
                    cardBody.appendChild(text);
                    cardInner.appendChild(image);
                    cardInner.appendChild(cardBody);
                    card.appendChild(cardInner);
                    rowContainer.appendChild(card);

                    if (currentUserName === 'Askep' || currentUserName === 'Manager' || currentUserName === 'Asisten') {
                        const buttonContainer = document.createElement("div");
                        buttonContainer.classList.add("btn-container");

                        const downloadLink = document.createElement("a");
                        downloadLink.href = "#"; // Set a placeholder link initially
                        downloadLink.innerHTML = '<i class="fas fa-download"></i> Download Image';
                        downloadLink.classList.add("btn", "btn-primary", "btn-sm");
                        buttonContainer.appendChild(downloadLink);

                        const uploading = document.createElement("a");
                        uploading.href = "#"; // Set a placeholder link initially
                        uploading.innerHTML = '<i class="fa fa-cloud-upload" aria-hidden="true"></i> Upload Image';
                        uploading.classList.add("btn", "btn-primary", "btn-sm");
                        buttonContainer.appendChild(uploading);

                        const deletes = document.createElement("a");
                        deletes.href = "#"; // Set a placeholder link initially
                        deletes.innerHTML = '<i class="fa fa-trash" aria-hidden="true"></i> Delete The Image';
                        deletes.classList.add("btn", "btn-primary", "btn-sm");
                        buttonContainer.appendChild(deletes);


                        const editkoment = document.createElement("a");
                        editkoment.href = "#"; // Set a placeholder link initially
                        editkoment.innerHTML = '<i class="fa fa-comments" aria-hidden="true"></i> Edit Komentar';
                        editkoment.classList.add("btn", "btn-primary", "btn-sm");
                        buttonContainer.appendChild(editkoment);
                        // Append the container to the card body
                        card.querySelector(".card-body").appendChild(buttonContainer);



                        deletes.addEventListener("click", () => {
                            // Display a confirmation dialog
                            Swal.fire({
                                title: 'Delete Confirmation',
                                text: 'Anda Yaking Ingin Menghapus Foto??',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Ya, Hapus',
                                cancelButtonText: 'Tidak',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // User confirmed deletion, proceed with the deletion logic

                                    // Hardcode the item type as 'perumahan' (change as needed)
                                    const itemType = 'lingkungan';

                                    // Construct the delete URL
                                    const deleteUrl = "https://srs-ssms.com/qc_inspeksi/uploadIMG.php";

                                    // Get the filename from the image URL
                                    const imageUrlParts = imageUrl.split('/');
                                    const filename = imageUrlParts[imageUrlParts.length - 1];

                                    // Create a FormData object to send the filename, item type, and action (delete)
                                    const formData = new FormData();
                                    formData.append('filename', filename); // Send the filename to be deleted
                                    formData.append('itemType', itemType); // Send the item type to the PHP script for validation
                                    formData.append('action', 'delete'); // Specify the action as 'delete'

                                    // Send a POST request to your PHP script for deletion
                                    fetch(deleteUrl, {
                                            method: 'POST',
                                            body: formData
                                        })
                                        .then(response => response.text())
                                        .then(result => {
                                            if (result === 'Image deleted successfully.') {
                                                // Display a success message using SweetAlert
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Delete Success',
                                                    text: 'The image was deleted successfully.',
                                                });

                                                // Reload the page with cache-busting
                                                location.reload(true); // Force a hard reload (including cache)
                                            } else {
                                                // Display an error message using SweetAlert
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Delete Error',
                                                    text: 'Error: ' + result, // Display the error message from the server
                                                });
                                            }
                                        })
                                        .catch(error => {
                                            // Display an error message using SweetAlert
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Delete Error',
                                                text: 'Error: ' + error, // Display the fetch error message
                                            });
                                        });
                                }
                            });
                        });
                        // Add click event to trigger download
                        downloadLink.addEventListener("click", () => {
                            // Use the image URL to construct the download URL
                            const downloadUrl = "https://srs-ssms.com/qc_inspeksi/get_qcIMG.php?image=" + encodeURIComponent(imageUrl);

                            // Open a new tab/window to initiate the download
                            window.open(downloadUrl, "_blank");
                        });

                        uploading.addEventListener("click", () => {
                            Swal.fire({
                                title: 'Select Image',
                                input: 'file',
                                inputAttributes: {
                                    accept: 'image/*',
                                    'aria-label': 'Upload your profile picture'
                                },
                                confirmButtonText: 'Upload',
                                showCancelButton: true,
                                cancelButtonText: 'Cancel',
                                inputValidator: (value) => {
                                    if (!value) {
                                        return 'You need to select an image!';
                                    }
                                }
                            }).then((file) => {
                                if (file.value) {
                                    const selectedFile = file.value;

                                    // Get the filename from the image URL
                                    const imageUrlParts = imageUrl.split('/');
                                    const filename = imageUrlParts[imageUrlParts.length - 1];

                                    // Hardcode the item type as 'perumahan'
                                    const itemType = 'lingkungan'; // Change this to 'landscape' or 'lingkungan' as needed

                                    // Construct the upload URL
                                    const uploadUrl = 'https://srs-ssms.com/qc_inspeksi/uploadIMG.php';

                                    // Create a FormData object to send the file, item type, and action (upload)
                                    const formData = new FormData();
                                    formData.append('image', selectedFile, filename); // Use the selected file with the correct filename
                                    formData.append('itemType', itemType); // Send the item type to the PHP script
                                    formData.append('action', 'upload'); // Specify the action as 'upload'

                                    // Create a new XMLHttpRequest object
                                    fetch(uploadUrl, {
                                            method: 'POST',
                                            body: formData
                                        })
                                        .then(response => response.text())
                                        .then(result => {
                                            if (result === 'File uploaded successfully.') {
                                                // Display a success message using SweetAlert
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Upload Success',
                                                    text: 'The image was uploaded successfully.',
                                                });

                                                location.reload(true);
                                            } else {
                                                // Display an error message using SweetAlert
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Upload Error',
                                                    text: 'Error: ' + result, // Display the error message from the server
                                                });
                                            }
                                        })
                                        .catch(error => {
                                            // Display an error message using SweetAlert
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Upload Error',
                                                text: 'Error: ' + error, // Display the fetch error message
                                            });
                                        });
                                }

                            });

                        });


                        editkoment.addEventListener("click", () => {
                            // Display a prompt dialog for the user to input a new comment

                            const id = data.id;
                            const komentar = data.komentar_temuan_lk;
                            const old_koment = data.komentar_temuan_lk;

                            let dataType = 'lingkunga_afd'
                            Swal.fire({
                                title: 'Edit Komentar',
                                input: 'text',
                                inputPlaceholder: komentar,
                                showCancelButton: true,
                                confirmButtonText: 'Save',
                                cancelButtonText: 'Cancel',
                                inputValidator: (value) => {
                                    if (!value) {
                                        return 'You need to enter a comment!';
                                    }
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    const newComment = result.value; // Get the user's input

                                    // Create an object with the parameters you want to send
                                    const params = {
                                        id: id,
                                        komen: newComment,
                                        old_koment: old_koment,
                                        dataType: dataType
                                    };
                                    var _token = $('input[name="_token"]').val();

                                    $.ajax({
                                        url: "{{ route('editkom') }}",
                                        method: "GET",
                                        data: params, // Use the params object here
                                        headers: {
                                            'X-CSRF-TOKEN': _token
                                        },
                                        success: function(result) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Berhasil',
                                                text: 'Komentar berhasil di update',
                                            });

                                            location.reload(true);
                                        },
                                        error: function(jqXHR, textStatus, errorThrown) {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Data Error',
                                                text: 'Error: ' + error, // Display the fetch error message
                                            });
                                        }
                                    });
                                }
                            });
                        });



                    }
                });
            } else {
                // If no data, show the "Perumahan not found" message
                const noDataMessage = document.createElement("p");
                noDataMessage.textContent = "Lingkungan not found.";
                container.appendChild(noDataMessage);
            }
        }


        function getTemuan() {
            var _token = $('input[name="_token"]').val();
            var estData = $("#est").val();
            var tanggal = $("#inputDate").val();
            // $perumahan.empty
            $('#perumahan').empty();
            $('#landscape').empty();
            $('#lingkungan').empty();
            $('#afd_rmh').empty();
            $('#afd_landscape').empty();
            $('#afd_lingkungan').empty();

            if ($$.fn.DataTable.isDataTable('#tabPerum')) {
                $$('#tabPerum').DataTable().destroy();
            }

            if ($$.fn.DataTable.isDataTable('#tablangscape')) {
                $$('#tablangscape').DataTable().destroy();
            }


            if ($$.fn.DataTable.isDataTable('#tablingk')) {
                $$('#tablingk').DataTable().destroy();
            }
            $.ajax({
                url: "{{ route('getTemuan') }}",
                method: "get",
                data: {
                    estData: estData,
                    tanggal: tanggal,
                    _token: _token
                },
                success: function(result) {
                    Swal.close();
                    var parseResult = JSON.parse(result);
                    var Perumahan = Object.entries(parseResult['Perumahan']);
                    var Landscape = Object.entries(parseResult['Landscape']);
                    var lingkungan = Object.entries(parseResult['lingkungan']);

                    var rumah_afd = Object.entries(parseResult['rumah_afd']);
                    var lcp_afd = Object.entries(parseResult['lcp_afd']);
                    var lingkungan_afd = Object.entries(parseResult['lingkungan_afd']);


                    // estate 
                    rumahupdate(Perumahan);
                    landscapeupdate(Landscape);
                    lingkunganupdate(lingkungan);

                    // afdeling 
                    afd_rmh(rumah_afd);
                    afd_landscape(lcp_afd);
                    afd_lingkungan(lingkungan_afd);


                    var dataPerum = $$('#tabPerum').DataTable({
                        columns: [{
                                title: 'ID',
                                data: 'id'
                            },
                            {
                                title: 'Estate',
                                data: 'est'
                            },
                            {
                                title: 'Afdeling',
                                data: 'afd'
                            },
                            {
                                title: 'Petugas',
                                data: 'petugas'
                            },
                            {
                                title: 'Pendamping',
                                data: 'pendamping'
                            },
                            {
                                title: 'Penghuni',
                                data: 'penghuni'
                            },
                            {
                                title: 'Tipe Rumah',
                                data: 'tipe_rumah'
                            },
                            {
                                title: 'Total Penilaian',
                                data: 'total_nilai'
                            },

                            {
                                // -1 targets the last column
                                title: 'Aksi',
                                visible: (currentUserName === 'Askep' || currentUserName === 'Asisten' || currentUserName === 'Manager'),
                                render: function(data, type, row, meta) {
                                    var buttons =
                                        '<button class="edit-btn">Edit Nilai</button>'
                                    return buttons;
                                }
                            }
                        ],
                    });

                    dataPerum.clear().rows.add(parseResult['tabPerum']).draw();

                    $('#tabPerum').on('click', '.edit-btn', function() {
                        var rowData = dataPerum.row($(this).closest('tr')).data();

                        $('#nilai_1').val(rowData.nilai_1);
                        $('#nilai_2').val(rowData.nilai_2);
                        $('#nilai_3').val(rowData.nilai_3);
                        $('#nilai_4').val(rowData.nilai_4);
                        $('#nilai_5').val(rowData.nilai_5);
                        $('#nilai_6').val(rowData.nilai_6);
                        $('#nilai_7').val(rowData.nilai_7);
                        $('#nilai_8').val(rowData.nilai_8);
                        $('#nilai_9').val(rowData.nilai_9);
                        $('#nilai_10').val(rowData.nilai_10);
                        $('#nilai_11').val(rowData.nilai_11);
                        $('#nilai_12').val(rowData.nilai_12);
                        $('#nilai_13').val(rowData.nilai_13);

                        $('#id').val(rowData.id);
                        $('#type').val('perumahan');

                        var myModal = new bootstrap.Modal(document.getElementById('editModal'));
                        myModal.show();
                        // Handle form submission
                        $('#editForm').submit(function(e) {
                            e.preventDefault(); // Prevent the default form submission

                            // Show SweetAlert confirmation
                            Swal.fire({
                                title: 'Simpan Data?',
                                text: 'Pastikan nilai benar sebelum menyimpan!',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    sendData(); // Call function to send AJAX request
                                }
                            });
                        });

                        // Function to send AJAX request with form data
                        function sendData() {
                            var nilaiArray = []; // Initialize an empty array

                            // Loop through nilai_1 to nilai_13 and push their values into the array
                            for (var i = 1; i <= 13; i++) {
                                nilaiArray.push($('#nilai_' + i).val());
                            }
                            var _token = $('input[name="_token"]').val();
                            var formData = {
                                nilai: nilaiArray, // Include the array in formData
                                id: $('#id').val(),
                                type: $('#type').val(),
                                _token: _token
                            };

                            // AJAX request
                            $.ajax({
                                type: 'post', // Change the method if needed
                                url: "{{ route('editNilai') }}",
                                data: formData,
                                success: function(response) {
                                    if (response.status === 'success') {
                                        // Handle success with SweetAlert
                                        Swal.fire({
                                            title: 'Success',
                                            text: 'Nilai berhasil diupdate',
                                            icon: 'success'
                                        }).then(function() {
                                            location.reload(); // Reload the page after success
                                        });
                                    } else {
                                        // Handle error with SweetAlert
                                        Swal.fire({
                                            title: 'Error',
                                            text: response.message || 'Failed to update nilai',
                                            icon: 'error'
                                        });
                                    }
                                },
                                error: function(error) {
                                    // Handle AJAX error
                                    console.error('Error sending data:', error);
                                    Swal.fire('Error', 'Failed to update nilai', 'error');
                                }
                            });




                        }
                    });



                    var dataLcp = $$('#tablangscape').DataTable({
                        columns: [{
                                title: 'ID',
                                data: 'id'
                            },
                            {
                                title: 'Estate',
                                data: 'est'
                            },
                            {
                                title: 'Afdeling',
                                data: 'afd'
                            },
                            {
                                title: 'Petugas',
                                data: 'petugas'
                            },
                            {
                                title: 'Pendamping',
                                data: 'pendamping'
                            },
                            {
                                title: 'Total Penilaian',
                                data: 'total_nilai'
                            },

                            {
                                // -1 targets the last column
                                title: 'Aksi',
                                visible: (currentUserName === 'Askep' || currentUserName === 'Asisten' || currentUserName === 'Manager'),
                                render: function(data, type, row, meta) {
                                    var buttons =
                                        '<button class="edit-btn">Edit Nilai</button>'
                                    return buttons;
                                }
                            }
                        ],
                    });

                    dataLcp.clear().rows.add(parseResult['tabLanscape']).draw();



                    $('#tablangscape').on('click', '.edit-btn', function() {
                        var rowData = dataLcp.row($(this).closest('tr')).data();

                        $('#nilai_l1').val(rowData.nilai_1);
                        $('#nilai_l2').val(rowData.nilai_2);
                        $('#nilai_l3').val(rowData.nilai_3);
                        $('#nilai_l4').val(rowData.nilai_4);
                        $('#nilai_l5').val(rowData.nilai_5);

                        $('#id').val(rowData.id);
                        $('#type').val('landscape');

                        var myModal = new bootstrap.Modal(document.getElementById('editModallandscape'));
                        myModal.show();
                        // Handle form submission
                        $('#editFormlandcsape').submit(function(e) {
                            e.preventDefault(); // Prevent the default form submission

                            // Show SweetAlert confirmation
                            Swal.fire({
                                title: 'Simpan Data?',
                                text: 'Pastikan nilai benar sebelum menyimpan!',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    sendData(); // Call function to send AJAX request
                                }
                            });
                        });

                        // Function to send AJAX request with form data
                        function sendData() {
                            var nilaiArray = []; // Initialize an empty array

                            // Loop through nilai_1 to nilai_13 and push their values into the array
                            for (var i = 1; i <= 5; i++) {
                                nilaiArray.push($('#nilai_l' + i).val());
                            }
                            var _token = $('input[name="_token"]').val();
                            var formData = {
                                nilai: nilaiArray, // Include the array in formData
                                id: $('#id').val(),
                                type: $('#type').val(),
                                _token: _token
                            };

                            // AJAX request
                            $.ajax({
                                type: 'post', // Change the method if needed
                                url: "{{ route('editNilai') }}",
                                data: formData,
                                success: function(response) {
                                    if (response.status === 'success') {
                                        // Handle success with SweetAlert
                                        Swal.fire({
                                            title: 'Success',
                                            text: 'Nilai berhasil diupdate',
                                            icon: 'success'
                                        }).then(function() {
                                            location.reload(); // Reload the page after success
                                        });
                                    } else {
                                        // Handle error with SweetAlert
                                        Swal.fire({
                                            title: 'Error',
                                            text: response.message || 'Failed to update nilai',
                                            icon: 'error'
                                        });
                                    }
                                },
                                error: function(error) {
                                    // Handle AJAX error
                                    console.error('Error sending data:', error);
                                    Swal.fire('Error', 'Failed to update nilai', 'error');
                                }
                            });
                        }
                    });


                    var dataLkn = $$('#tablingk').DataTable({
                        columns: [{
                                title: 'ID',
                                data: 'id'
                            },
                            {
                                title: 'Estate',
                                data: 'est'
                            },
                            {
                                title: 'Afdeling',
                                data: 'afd'
                            },
                            {
                                title: 'Petugas',
                                data: 'petugas'
                            },
                            {
                                title: 'Pendamping',
                                data: 'pendamping'
                            },
                            {
                                title: 'Total Penilaian',
                                data: 'total_nilai'
                            },

                            {
                                // -1 targets the last column
                                title: 'Aksi',
                                visible: (currentUserName === 'Askep' || currentUserName === 'Asisten' || currentUserName === 'Manager'),
                                render: function(data, type, row, meta) {
                                    var buttons =
                                        '<button class="edit-btn">Edit Nilai</button>'
                                    return buttons;
                                }
                            }
                        ],
                    });

                    dataLkn.clear().rows.add(parseResult['tabLingkn']).draw();

                    $('#tablingk').on('click', '.edit-btn', function() {
                        var rowData = dataLkn.row($(this).closest('tr')).data();

                        $('#nilai_k1').val(rowData.nilai_1);
                        $('#nilai_k2').val(rowData.nilai_2);
                        $('#nilai_k3').val(rowData.nilai_3);
                        $('#nilai_k4').val(rowData.nilai_4);
                        $('#nilai_k5').val(rowData.nilai_5);
                        $('#nilai_k6').val(rowData.nilai_6);
                        $('#nilai_k7').val(rowData.nilai_7);
                        $('#nilai_k8').val(rowData.nilai_8);
                        $('#nilai_k9').val(rowData.nilai_9);
                        $('#nilai_k10').val(rowData.nilai_10);
                        $('#nilai_k11').val(rowData.nilai_11);
                        $('#nilai_k12').val(rowData.nilai_12);
                        $('#nilai_k13').val(rowData.nilai_13);
                        $('#nilai_k14').val(rowData.nilai_14);


                        $('#id').val(rowData.id);
                        $('#type').val('lingkungan');



                        var myModal = new bootstrap.Modal(document.getElementById('editModallkungan'));
                        myModal.show();
                        // Handle form submission
                        $('#editFormlingkn').submit(function(e) {
                            e.preventDefault(); // Prevent the default form submission

                            // Show SweetAlert confirmation
                            Swal.fire({
                                title: 'Simpan Data?',
                                text: 'Pastikan nilai benar sebelum menyimpan!',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    sendData(); // Call function to send AJAX request
                                }
                            });
                        });

                        // Function to send AJAX request with form data
                        function sendData() {
                            var nilaiArray = []; // Initialize an empty array

                            // Loop through nilai_1 to nilai_13 and push their values into the array
                            for (var i = 1; i <= 14; i++) {
                                nilaiArray.push($('#nilai_k' + i).val());
                            }
                            var _token = $('input[name="_token"]').val();
                            var formData = {
                                nilai: nilaiArray, // Include the array in formData
                                id: $('#id').val(),
                                type: $('#type').val(),
                                _token: _token
                            };

                            // AJAX request
                            $.ajax({
                                type: 'post', // Change the method if needed
                                url: "{{ route('editNilai') }}",
                                data: formData,
                                success: function(response) {
                                    if (response.status === 'success') {
                                        // Handle success with SweetAlert
                                        Swal.fire({
                                            title: 'Success',
                                            text: 'Nilai berhasil diupdate',
                                            icon: 'success'
                                        }).then(function() {
                                            location.reload(); // Reload the page after success
                                        });
                                    } else {
                                        // Handle error with SweetAlert
                                        Swal.fire({
                                            title: 'Error',
                                            text: response.message || 'Failed to update nilai',
                                            icon: 'error'
                                        });
                                    }
                                },
                                error: function(error) {
                                    // Handle AJAX error
                                    console.error('Error sending data:', error);
                                    Swal.fire('Error', 'Failed to update nilai', 'error');
                                }
                            });
                        }
                    });


                },
                error: function(xhr, status, error) {
                    // Handle the error here
                    console.error('AJAX request error:', error);
                }

            });
        }


        document.addEventListener("DOMContentLoaded", function() {
            var selectedDate = document.getElementById("inputDate");
            selectedDate.addEventListener("change", function() {
                const selectedDate = inputDate.value;
                document.getElementById("downloadba").disabled = false;
                document.getElementById("downloadpdf").disabled = false;
                document.getElementById("tglPDF").value = selectedDate;
                document.getElementById("tglpdfnew").value = selectedDate;
            });
            // if (selectedDate) {
            //     document.getElementById("downloadba").disabled = false;
            //     document.getElementById("downloadpdf").disabled = false;
            //     document.getElementById("tglPDF").value = selectedDate;
            //     document.getElementById("tglpdfnew").value = selectedDate;
            // } else {
            //     alert("Please select a date first.");
            // }
        });




        function downloadpdf() {
            // Get the selected date from the inputDate select element
            var selectedDate = $("#inputDate").val();

            // Set the value of the tglPDF hidden input field
            $("#tglPDF").val(selectedDate);

            // Submit the form
            $("#download-form").submit();


        }

        function downloadPDFpi() {
            var selectedDate = $("#inputDate").val();
            $("#tglpdfnew").val(selectedDate);

            $("#downloadPDF").submit();
        }

        var listafd = @json($listafd);
        var estdetail = @json($est);
        if (currentUserName === 'Askep' || currentUserName === 'Manager' || currentUserName === 'Asisten') {

            $('#addnewimg').click(function() {
                console.log('adding new img')

                // Create options for afdSelect dropdown
                var afdOptions = '';
                listafd.forEach(function(item) {
                    afdOptions += `<option value="${item}">${item}</option>`;
                });

                // Example using SweetAlert 2
                Swal.fire({
                    title: 'Tambah Foto Baru',
                    html: `
            
            <p> Tipe </p> <select id="typeSelect" class="swal2-select">
                <option value="perumahan">Perumahan</option>
                <option value="landscape">Landscape</option>
                <option value="lingkungan">Lingkungan</option>
            </select>
            <p> Afdeling </p>
            <select id="afdSelect" class="swal2-select">
                ${afdOptions}
            </select>
            <p> Komentar </p>
            <textarea id="komentar" style="height: 100px; width: 300px; resize: none;"></textarea>
            <input type="file" id="imageInput" class="swal2-input" accept=".jpg, .jpeg, .png">
        `,
                    showCancelButton: true,
                    confirmButtonText: 'Save Image',
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        const imageFile = document.getElementById('imageInput').files[0];
                        const typeSelected = document.getElementById('typeSelect').value;
                        const afdSelected = document.getElementById('afdSelect').value;
                        const inputDate = document.getElementById('inputDate').value;
                        const komentar = $('#komentar').val();
                        const estate = estdetail;

                        // Check if the image file is not null
                        if (!imageFile) {
                            Swal.showValidationMessage('Harap Pilih Gambar!');
                            return false; // Stops the modal from closing
                        }

                        const formData = new FormData();
                        formData.append('image', imageFile);
                        formData.append('type', typeSelected);
                        formData.append('afd', afdSelected);
                        formData.append('tanggal', inputDate);
                        formData.append('estate', estate);
                        formData.append('komentar', komentar);
                        formData.append('_token', $('input[name="_token"]').val());

                        // console.log(formData);



                        // AJAX request
                        $.ajax({
                            type: 'post',
                            url: "{{ route('adingnewimg') }}",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                if (response.status === 'success') {
                                    // Handle success with SweetAlert
                                    Swal.fire({
                                        title: 'Success',
                                        text: 'Foto berhasil diUpload',
                                        icon: 'success'
                                    }).then(function() {
                                        location.reload(); // Reload the page after success
                                    });
                                } else {
                                    // Handle error with SweetAlert
                                    Swal.fire({
                                        title: 'Error',
                                        text: response.message || 'Failed to update ',
                                        icon: 'error'
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                // Handle AJAX error
                                console.error('Error sending data:', error);
                                Swal.fire('Error', 'Failed to update nilai', 'error');
                            }
                        });

                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then(result => {
                    if (result.isConfirmed) {
                        Swal.fire(
                            'Saved!',
                            'Image has been saved.',
                            'success'
                        );
                    }
                });
            });
        }
    </script>
</x-layout.app>