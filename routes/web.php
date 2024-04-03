<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\unitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\SidaktphController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TesExportController;
use App\Http\Controllers\inspectController;
use App\Http\Controllers\mutubuahController;
use App\Http\Controllers\userNewController;
use App\Http\Controllers\UserQCController;
use App\Http\Controllers\emplacementsController;
use App\Http\Controllers\taksasiController;
use App\Http\Controllers\adminpanelController;
use App\Http\Controllers\incpectcomponent\inspeksidashController;
use App\Http\Controllers\incpectcomponent\pdfgenerateController;
use App\Http\Controllers\incpectcomponent\makemapsController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\RekapController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });



Route::get('/', [LoginController::class, 'index'])->name('logina');
Route::post('/', [loginController::class, 'authenticate'])->name('login');
Route::post('logout', [loginController::class, 'logout'])->name('logout');
Route::get('/gettaksasi/{query}', [taksasiController::class, 'dashboard']);
Route::middleware(['auth'])->group(function () {
    Route::get('/index', [unitController::class, 'index']);
    Route::get('/dashboard', [unitController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard_gudang', [unitController::class, 'dashboard_gudang'])->name('dashboard_gudang');
    Route::get('/dashboardtph', [SidaktphController::class, 'index'])->name('dashboardtph');
    Route::get('/listAsisten', [SidaktphController::class, 'listAsisten'])->name('listAsisten');
    Route::post('/tambahAsisten', [SidaktphController::class, 'tambahAsisten'])->name('tambahAsisten');
    Route::post('/perbaruiAsisten', [SidaktphController::class, 'perbaruiAsisten'])->name('perbaruiAsisten');
    Route::post('/hapusAsisten', [SidaktphController::class, 'hapusAsisten'])->name('hapusAsisten');
    Route::post('/getData', [SidaktphController::class, 'getData'])->name('getData');
    Route::post('/dashboardtph', [SidaktphController::class, 'chart'])->name('chart');
    Route::get('/getBtTph', [SidaktphController::class, 'getBtTph'])->name('getBtTph');
    Route::get('/getBtTphMonth', [SidaktphController::class, 'getBtTphMonth'])->name('getBtTphMonth');
    Route::get('/getBtTphYear', [SidaktphController::class, 'getBtTphYear'])->name('getBtTphYear');
    Route::get('/graphFilterYear', [SidaktphController::class, 'graphFilterYear'])->name('graphFilterYear');
    Route::post('/getKrTph', [SidaktphController::class, 'getKrTph'])->name('getKrTph');
    Route::post('/getBHtgl', [SidaktphController::class, 'getBHtgl'])->name('getBHtgl');
    Route::post('/exportPDF', [SidaktphController::class, 'exportPDF'])->name('exportPDF');
    Route::post('/downloadPDF', [SidaktphController::class, 'downloadPDF'])->name('downloadPDF');
    Route::get('/changeDataTph', [SidaktphController::class, 'changeDataTph'])->name('changeDataTph');
    Route::post('/changeRegionEst', [SidaktphController::class, 'changeRegionEst'])->name('changeRegionEst');
    // Route::get('/404', [SidaktphController::class, 'notfound'])->name('404');
    Route::get('/tambah', [unitController::class, 'tambah']);
    Route::post('/store', [unitController::class, 'store']);
    Route::get('/edit/{id}', [unitController::class, 'edit']);
    Route::post('/update', [unitController::class, 'update']);
    Route::get('/hapus/{id}', [unitController::class, 'hapus']);
    Route::get('detailInspeksi/{id}', [unitController::class, 'detailInspeksi'])->name('detailInspeksi');
    Route::get('detailSidakTph/{est}/{afd}/{start}/{last}', [SidaktphController::class, 'detailSidakTph'])->name('detailSidakTph');
    Route::get('getPlotLine', [SidaktphController::class, 'getPlotLine'])->name('getPlotLine');
    Route::get('/qc', [unitController::class, 'load_qc_gudang'])->name('qc');
    Route::get('/cetakpdf/{id}', [unitController::class, 'cetakpdf'])->name('cetakpdf');

    Route::get('/hapusRecord/{id}', [unitController::class, 'hapusRecord'])->name('hapusRecord');
    Route::get('/getDataByYear', [unitController::class, 'getDataByYear'])->name('getDataByYear');

    Route::get('/dashboard_inspeksi', [inspectController::class, 'dashboard_inspeksi'])->name('dashboard_inspeksi');

    Route::get('/getFindData', [inspectController::class, 'getFindData'])->name('getFindData');
    Route::get('/changeDataInspeksi', [inspectController::class, 'changeDataInspeksi'])->name('changeDataInspeksi');
    Route::post('/plotEstate', [inspectController::class, 'plotEstate'])->name('plotEstate');

    // Route::post('/filter', [inspectController::class, 'filter']);

    // yang di ubah 
    Route::get('/cetakPDFFI/{id}/{est}/{tgl}', [pdfgenerateController::class, 'cetakPDFFI'])->name('cetakPDFFI');
    Route::get('/filter', [inspeksidashController::class, 'filterv2'])->name('filter');
    Route::get('/graphfilter', [inspeksidashController::class, 'graphfilter'])->name('graphfilter');
    Route::get('/filterTahun', [inspeksidashController::class, 'filterTahun'])->name('filterTahun');
    Route::post('/updateBA', [pdfgenerateController::class, 'updateBA'])->name('updateBA');
    Route::post('/deleteBA', [pdfgenerateController::class, 'deleteBA'])->name('deleteBA');
    Route::delete('/deleteTrans/{id}', [pdfgenerateController::class, 'deleteTrans'])->name('deleteTrans');
    Route::post('/pdfBA', [pdfgenerateController::class, 'pdfBA'])->name('pdfBA');
    Route::get('/getMapsdetail', [makemapsController::class, 'getMapsdetail'])->name('getMapsdetail');
    Route::get('/plotBlok', [makemapsController::class, 'plotBlok'])->name('plotBlok'); // Route::post('/filter', [inspectController::class, 'filter']);

    // done ubah 



    Route::get('detailInpeksi/{est}/{afd}/{date}', [inspectController::class, 'detailInpeksi'])->name('detailInpeksi');
    Route::get('dataDetail/{est}/{afd}/{date}/{reg}', [inspectController::class, 'dataDetail'])->name('dataDetail');
    Route::get('filterDataDetail', [inspectController::class, 'filterDataDetail'])->name('filterDataDetail');

    Route::post('/editNilai', [emplacementsController::class, 'editNilai'])->name('editNilai');

    Route::post('/fetchEstatesByRegion', [inspectController::class, 'fetchEstatesByRegion'])->name('fetchEstatesByRegion');


    Route::get('/listktu', [unitController::class, 'listktu'])->name('listktu');
    Route::post('/get-estate-list-ktu', [unitController::class, 'getEstateListKtu'])->name('get-estate-list-ktu');
    Route::post('/tambahKTU', [unitController::class, 'tambahKTU'])->name('tambahKTU');
    Route::post('/updateKTU', [unitController::class, 'updateKTU'])->name('updateKTU');
    Route::post('/hapusKTU', [unitController::class, 'hapusKTU'])->name('hapusKTU');

    Route::post('/hapusDetailSidak', [SidaktphController::class, 'hapusDetailSidak'])->name('hapusDetailSidak');
    // Route::get('BaSidakTPH/{est}/{start}/{last}', [SidaktphController::class, 'BasidakTph'])->name('BasidakTph');
    Route::get('BaSidakTPH/{est}/{afd}/{tanggal}/{regional}', [SidaktphController::class, 'BasidakTph'])->name('BasidakTph');

    Route::get('/dashboard_mutubuah', [mutubuahController::class, 'dashboard_mutubuah'])->name('dashboard_mutubuah');
    Route::get('/getWeek', [MutubuahController::class, 'getWeek'])->name('getWeek');
    Route::get('/getYear', [MutubuahController::class, 'getYear'])->name('getYear');
    Route::get('/getYearData', [MutubuahController::class, 'getYearData'])->name('getYearData');
    Route::get('/findingIsueTahun', [MutubuahController::class, 'findingIsueTahun'])->name('findingIsueTahun');
    Route::get('/getWeekData', [MutubuahController::class, 'getWeekData'])->name('getWeekData');
    Route::get('/getahun_sbi', [MutubuahController::class, 'getahun_sbi'])->name('getahun_sbi');
    Route::post('/findIssueSmb', [MutubuahController::class, 'findIssueSmb'])->name('findIssueSmb');
    Route::get('/cetakFiSmb/{est}/{tgl}', [MutubuahController::class, 'cetakFiSmb'])->name('cetakFiSmb');

    Route::get('filtersidaktphrekap', [SidaktphController::class, 'filtersidaktphrekap'])->name('filtersidaktphrekap');
    Route::post('/deleteBAsidakTPH', [SidaktphController::class, 'deleteBAsidakTPH'])->name('deleteBAsidakTPH');
    Route::post('/updateBASidakTPH', [SidaktphController::class, 'updateBASidakTPH'])->name('updateBASidakTPH');
    Route::get('pdfBAsidak', [SidaktphController::class, 'pdfBAsidak'])->name('pdfBAsidak');

    Route::get('/cetakmutubuah_id/{est}/{tahun}/{reg}', [MutubuahController::class, 'cetakmutubuahsidak'])->name('cetakmutubuahsidak');
    Route::get('/chartsbi_oke', [MutubuahController::class, 'chartsbi_oke'])->name('chartsbi_oke');

    Route::get('detailtmutubuah/{est}/{afd}/{bulan}', [MutubuahController::class, 'detailtmutubuah'])->name('detailtmutubuah');
    Route::get('filterdetialMutubuah', [MutubuahController::class, 'filterdetialMutubuah'])->name('filterdetialMutubuah');
    Route::post('/updateBA_mutubuah', [MutubuahController::class, 'updateBA_mutubuah'])->name('updateBA_mutubuah');
    Route::post('/deleteBA_mutubuah', [MutubuahController::class, 'deleteBA_mutubuah'])->name('deleteBA_mutubuah');
    Route::post('/pdfBA_sidakbuah', [MutubuahController::class, 'pdfBA_sidakbuah'])->name('pdfBA_sidakbuah');

    Route::get('/User/user', [userNewController::class, 'showUser'])->name('user.show');
    Route::post('/getuser', [userNewController::class, 'getuser'])->name('getuser');
    Route::post('/update_user', [userNewController::class, 'update_user'])->name('update_user');
    Route::get('/listAsisten2', [userNewController::class, 'listAsisten2'])->name('listAsisten2');
    Route::post('/updateAsisten', [userNewController::class, 'updateAsisten'])->name('updateAsisten');
    Route::post('/deleteAsisten', [userNewController::class, 'deleteAsisten'])->name('deleteAsisten');
    Route::post('/storeAsisten', [userNewController::class, 'storeAsisten'])->name('storeAsisten');


    Route::get('/getWeekInpeksi', [inspectController::class, 'getWeekInpeksi'])->name('getWeekInpeksi');
    Route::post('/pdfBA_excel', [inspectController::class, 'pdfBA_excel'])->name('pdfBA_excel');
    Route::get('/getDataRekap', [MutubuahController::class, 'getDataRekap'])->name('getDataRekap');
    Route::post('/WeeklyReport', [MutubuahController::class, 'weeklypdf'])->name('WeeklyReport');
    Route::get('/getDataDay', [inspectController::class, 'getDataDay'])->name('getDataDay');

    Route::get('getMapsTph', [SidaktphController::class, 'getMapsTph'])->name('getMapsTph');

    Route::get('/getMapsData', [MutubuahController::class, 'getMapsData'])->name('getMapsData');

    Route::get('detailEmplashmend/{est}/{date}', [emplacementsController::class, 'detailEmplashmend'])->name('detailEmplashmend');
    Route::get('getDateBA/{est}/{date}', [emplacementsController::class, 'getDateBA'])->name('getDateBA');
    Route::get('/dashboard_perum', [emplacementsController::class, 'dashboard_perum'])->name('dashboard_perum');
    Route::get('/getAFD', [emplacementsController::class, 'getAFD'])->name('getAFD');
    Route::get('/estAFD', [emplacementsController::class, 'estAFD'])->name('estAFD');


    Route::get('/getTemuan', [emplacementsController::class, 'getTemuan'])->name('getTemuan');
    Route::post('/downloadBAemp', [emplacementsController::class, 'downloadBAemp'])->name('downloadBAemp');
    Route::post('/downloadPDF', [emplacementsController::class, 'downloadPDF'])->name('downloadPDF');


    Route::post('/updatesidakTPhnew', [SidaktphController::class, 'updatesidakTPhnew'])->name('updatesidakTPhnew');

    Route::post('/deletedetailtph', [SidaktphController::class, 'deletedetailtph'])->name('deletedetailtph');

    Route::get('/userqcpanel', [adminpanelController::class, 'dashboard'])->name('userqcpanel');
    Route::get('/listqc', [adminpanelController::class, 'listqc'])->name('listqc');
    Route::post('/updateUserqc', [adminpanelController::class, 'updateUserqc'])->name('updateUserqc');
    Route::get('/editkom', [emplacementsController::class, 'editkom'])->name('editkom');


    Route::post('/downloadMaptahun', [makemapsController::class, 'downloadMaptahun'])->name('downloadMaptahun');
    Route::get('/pdfPage/{filename}/{est}', [makemapsController::class, 'pdfPage'])->name('pdfPage');
    Route::post('/getimgqc', [inspectController::class, 'getimgqc'])->name('getimgqc');




    Route::get('/dashboardabsensi', [AbsensiController::class, 'index'])->name('dashboardabsensi');
    Route::get('/absensidata', [AbsensiController::class, 'data'])->name('absensidata');
    Route::get('/absenmaps', [AbsensiController::class, 'getMaps'])->name('absenmaps');
    Route::get('/absensipdf', [AbsensiController::class, 'exportPDF'])->name('absensipdf');
    Route::get('/absensibukti', [AbsensiController::class, 'getimgBukti'])->name('absensibukti');
    Route::get('/getEditabsensi', [AbsensiController::class, 'getEdit'])->name('getEditabsensi');
    Route::post('/crudabsensi', [AbsensiController::class, 'crudAbsensi'])->name('crudabsensi');
    Route::post('/creatAbsen', [AbsensiController::class, 'creatAbsen'])->name('creatAbsen');



    Route::post('/adingnewimg', [emplacementsController::class, 'adingnewimg'])->name('adingnewimg');

    Route::get('/rekap', [RekapController::class, 'index'])->name('rekap');
    Route::get('/olahdata', [RekapController::class, 'olahdata'])->name('olahdata');
    Route::get('/getdatayear', [RekapController::class, 'getdatayear'])->name('allskoreyear');
    Route::get('/getdataweek', [RekapController::class, 'getdataweek'])->name('getdataweek');
    Route::get('/getestatesidakmtbuah', [MutubuahController::class, 'getestatesidakmtbuah'])->name('getestatesidakmtbuah');
    Route::post('/duplicatesidakmtb', [MutubuahController::class, 'duplicatesidakmtb'])->name('duplicatesidakmtb');
    Route::post('/changedatadate', [MutubuahController::class, 'changedatadate'])->name('changedatadate');
    Route::get('/pdfmutubuhuahdata/{reg}/{est}', [MutubuahController::class, 'pdfmutubuhuahdata'])->name('pdfmutubuhuahdata');
    Route::get('/pdfsidaktphdata/{reg}/{est}', [SidaktphController::class, 'pdfsidaktphdata'])->name('pdfsidaktphdata');
    Route::get('/excelqcinspeksi/{reg}/{est}', [inspectController::class, 'excelqcinspeksi'])->name('excelqcinspeksi');
    Route::get('/getmonthrh', [RekapController::class, 'getmonthrh'])->name('getmonthrh');
});

Route::get('/user_qc/{lokasi_kerja}', [UserQCController::class, 'index'])->name('user_qc');
Route::get('/create', [UserQCController::class, 'create'])->name('create');
Route::post('/store/{lokasi_kerja}', [UserQCController::class, 'store'])->name('store');
Route::get('/edit/{id}', [UserQCController::class, 'edit'])->name('edit');
Route::post('/update/{id}/{lokasi_kerja}', [UserQCController::class, 'update'])->name('update');
Route::post('/delete/{id}', [UserQCController::class, 'destroy'])->name('delete');
