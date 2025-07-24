<?php

use Faker\Provider\ar_EG\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Dashboard\HomeController;
use App\Http\Controllers\Payments\PaymentController;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Inspections\InspectionsController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', function () {
    return view('userAuth.signin');
})->name('login')->middleware('session.exists');

Route::get('signup', function () {
    return view('userAuth.signup');
})->name('signup')->middleware('session.exists');

Route::get('/user/passwordreset', function () {
    return view('userAuth.forgotpassword');
})->name('forgotpassword')->middleware('session.exists');





// Route::group(['middleware' => ['confirmed' ]] , function(){
//     Route::get('/home' , [AuthenticationController::class , "dashboard"])->name('dashboard');
//     Route::get('/password/change' , [HomeController::class , "changepassword"])->name('password-change');
//     Route::get('/profile' , [HomeController::class , 'profile'] )->name('profile');

//     //Licence a school
//     Route::get('/license/school' , [HomeController::class , 'licenseSchool'])->name('license');
//     Route::get('/license/new-school' , [HomeController::class , 'NewSchool'])->name('newSchool');

//     Route::post('/logout' , [AuthenticationController::class , "logout"])->name('logout');

// });

Route::middleware(['check.session'])->group(function () {
    Route::get('/home', [AuthenticationController::class, 'dashboard'])->name('dashboard');
    Route::get('/password/change', [HomeController::class, "changepassword"])->name('password-change');
    Route::get('/profile', [HomeController::class, 'profile'])->name('profile');

    // Licence a school
    Route::get('/register/school', [HomeController::class, 'registerSchool'])->name('register-school');
    Route::get('/license/school', [HomeController::class, 'licenseSchool'])->name('license');
    Route::get('/license/new-school', [HomeController::class, 'NewSchool'])->name('newSchool');
    Route::get('/viewapplication/{special_school_id}', [HomeController::class, 'viewApplication'])->name('viewApplication');
    Route::get('/schools/pending-license-renewal', [HomeController::class, 'viewSchoolPendingRenewal'])->name('view-sch-pending-renewal');
    Route::get('/schools/not-pending-license-renewal', [HomeController::class, 'viewSchoolNotPendingRenewal'])->name('view-sch-not-pending-renewal');

    //Application for authorisation
    Route::get('/applicationforauthorisation/payforapplication/{applicationId}', [PaymentController::class, 'payForAfa'])->name('payForAfa');
    Route::get('/applicationforauthorisation/paymentsuccess', [PaymentController::class, 'afapaymentsuccess'])->name('afapaymentsuccess');
    Route::get('/applicationforauthorisation', [HomeController::class, 'applicationforauthorisation'])->name('applicationforauthorisation');
    Route::get('/applicationforauthorisation/document/{applicationId}', [HomeController::class, 'afadocument'])->name('afadocument');
    Route::get('/applicationforauthorisation/submitapplication/{applicationId}', [HomeController::class, 'submitAfa'])->name('submitAfa');

    //Expression of interest
    Route::get('/expressionofinterest/payforapplication/{applicationId}', [PaymentController::class, 'payForEoi'])->name('payForEoi');
    Route::get('/expressionofinterest/paymentsuccess', [PaymentController::class, 'eoipaymentsuccess'])->name('eoipaymentsuccess');
    Route::get('/expressionofinterest/schooldetails', [HomeController::class, 'eoiSchoolDetails'])->name('schoolDetails');
    Route::get('/expressionofinterest/proprietorinfo/{applicationId}', [HomeController::class, 'proprietorInformation'])->name('proprietorInformation');
    Route::get('/expressionofinterest/organisationdatails/{applicationId}', [HomeController::class, 'organisationDetails'])->name('editOrganisationDetails');
    Route::get('/expressionofinterest/individauldatails/{applicationId}', [HomeController::class, 'individualDetails'])->name('editIndividualDetails');
    Route::get('/expressionofinterest/document/{applicationId}', [HomeController::class, 'eoidocument'])->name('eoiDocument');
    Route::get('/expressionofinterest/submitapplication/{applicationId}', [HomeController::class, 'submitEoi'])->name('submitEoi');
    Route::get('/expressionofinterest/editownershipdetails', [HomeController::class, 'expressionofinterest'])->name('expressionofinterest');
    Route::get('/expressionofinterest/editschooldetails', [HomeController::class, 'editschooldetails'])->name('editEoidetails');

    //Notice of intent
    Route::get('/noticeofintent/payforapplication/{applicationId}', [PaymentController::class, 'payForNoi'])->name('payForNoi');
    Route::get('/noticeofintent/paymentsuccess', [PaymentController::class, 'noipaymentsuccess'])->name('noipaymentsuccess');
    Route::get('/noticeofintent', [HomeController::class, 'noticeOfIntentSchoolDetails'])->name('noticeofintentschooldetails');
    Route::get('/noticeofintent/schoolfacilities/{applicationId}', [HomeController::class, 'noticeOfIntentSchoolFacilities'])->name('noticeofintentschoolfacilities');
    Route::get('/noticeofintent/schoolfacilitychecklist/{applicationId}', [HomeController::class, 'noticeofintentSchoolFacilityChecklist'])->name('noticeofintentschoolfacilitychecklist');
    Route::get('/noticeofintent/schoolleadership{applicationId}', [HomeController::class, 'noticeofintentSchoolLeadership'])->name('noticeofintentschoolleadership');
    Route::get('/noticeofintent/schoolfeesstructure{applicationId}', [HomeController::class, 'noticeofintentSchoolFees'])->name('noticeofintentschoolfees');
    Route::get('/noticeofintent/documents/{applicationId}', [HomeController::class, 'noticeofintentDocuments'])->name('noticeofintentdocuments');
    Route::get('/noticeofintent/submitapplication/{applicationId}', [HomeController::class, 'noticeofintentSubmit'])->name('submitNoi');

    //Letter of introduction
    Route::get('/letterofintroduction/payforapplication/{applicationId}', [PaymentController::class, 'payForLoi'])->name('payForLoi');
    Route::get('/letterofintroduction', [HomeController::class, 'letterofintroductionSchoolDetails'])->name('loischooldetails');

    //payments and arrears
    Route::get('/allpayments' , [PaymentController::class, 'allPayments'])->name('all-payments');
    Route::get('/arrears', [PaymentController::class, 'arrears'])->name('arrears');

    //inspections
    Route::get('/inspection-reports', [InspectionsController::class, 'inspectionReport'])->name('inspection-reports');


    Route::post('/logout', [AuthenticationController::class, "logout"])->name('logout');
});





Route::get('/expressionofinterest/page2', function () {
    return view('expressionofinterest.page2');
});
Route::get('/expressionofinterest/page3', function () {
    return view('expressionofinterest.page3');
});


Route::get('/applicationforauthorisation/page1', function () {
    return view('applicationforauthorisation.page1');
});
Route::get('/applicationforauthorisation/page2', function () {
    return view('applicationforauthorisation.page2');
});


Route::get('/noticeofintent/page1', function () {
    return view('noticeofintent.page1');
});
Route::get('/noticeofintent/page2', function () {
    return view('noticeofintent.page2');
});
Route::get('/noticeofintent/page3', function () {
    return view('noticeofintent.page3');
});
Route::get('/noticeofintent/page3b', function () {
    return view('noticeofintent.page3b');
});
Route::get('/noticeofintent/page3c', function () {
    return view('noticeofintent.page3c');
});
Route::get('/noticeofintent/page4', function () {
    return view('noticeofintent.page4');
});
Route::get('/noticeofintent/page6', function () {
    return view('noticeofintent.page6');
});
Route::get('/noticeofintent/page7', function () {
    return view('noticeofintent.page7');
});
Route::get('/noticeofintent/payment', function () {
    return view('noticeofintent.payment');
});
Route::get('/noticeofintent/departselection', function () {
    return view('noticeofintent.departselection');
});
Route::get('/noticeofintent/departselection2', function () {
    return view('noticeofintent.departselection2');
});



Route::get('/registeredschools/page1', function () {
    return view('registeredschools.page1');
});
Route::get('/registeredschools/page2', function () {
    return view('registeredschools.page2');
});

Route::get('/letterofintroduction/page1', function () {
    return view('letterofintroduction.PAGE1');
});


Route::get('/submission', function () {
    return view('submission');
});






Route::get('/user/signup', function () {
    return view('userAuth.signup');
});
