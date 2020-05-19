<?php

 

Auth::routes();
Route::get('hrmanage','HrController@indexhr');
Route::get('sendinvite','HrController@sendinvite');
Route::post('/invitation','HrController@send');
Route::get('interview','HrController@student');
Route::post('/attend','HrController@attend');
Route::get('/hrround','HrController@hrtake');
Route::get('/getround','HrController@getround');
Route::POST('/firstmarks','HrController@firstmarks');
Route::POST('/secmarks','HrController@secmarks');
Route::get('/acceptboss','HrController@acceptboss');
Route::get('/rejectboss','HrController@rejectboss');
Route::get('/respond','HrController@respond');
Route::get('/selectuser','HrController@selectuser');
Route::get('/blocked_manu','CustomerController@manublocked');
Route::post('/toggle-manu',"CustomerController@approval1manu");
Route::get('/manuunupdated','CustomerController@Unupdated');
Route::get('/cancelorders','CustomerController@cancelorderf');
Route::get('/getgstvalue','HrController@getgstvalue');
Route::get('/getgstvalue1','HrController@getgstvalue1');
Route::get('/getgstvalue12','HrController@getgstvalue12');
Route::get('/getdistict','HrController@getdistict');
Route::get('/pendingorders','AdminController@pendingorders');

Route::get('/approveorder','AdminController@approveorder');

//Error Handling

Route::get('/deleteddata','HrController@delete');
Route::get('/default','HrController@default');

Route::get('/multipleinvoice','FinanceDashboard@multipleinvoice');
Route::post('/multipleinvoicedata','FinanceDashboard@multidata');
Route::get('/combinepurchase','FinanceDashboard@demodata');
Route::get('/customertype','amController@getcustomer');
Route::get('/getinvoice','amController@getinvoice');
Route::POST('/customergeneration','HrController@customergeneration');
Route::get('/getsupliers','HrController@getsupliers');
Route::get('/getsuplierid','HrController@getsuplierid');
Route::get('/getpostalcode','HrController@getpostalcode');
Route::get('/customermap','HrController@customermap');
Route::get('/customerprojects','HrController@customerprojects');
Route::post('/customerdetailslist','HrController@customerdetailslist');
Route::get('/customerlist','HrController@customerlist');
Route::get('/bankloan','HrController@bankloan');
Route::post('/fixdate','HrController@fixdate');
Route::get('/reassign','HrController@reassign');
Route::get('/getcustomerid','HrController@getcustomerid');
Route::get('/loadsubcust','HrController@loadsubcust');
Route::post('/graderange','HrController@graderange');
Route::get('/productquatation','HrController@quatation');

Route::post('/getproductquan','HrController@getproductquan');
Route::post('/getgstdata','HrController@getgstdata');
Route::get('/deletequan','HrController@deletequan');
Route::post('/updatepaymentmode','HrController@updatepaymentmode');
Route::post('/updatepaymentmode1','HrController@updatepaymentmode1');
Route::get('/getcustomerinvoices','HrController@getcustomerinvoices');
Route::get('/getAprrovalpage','HrController@getAprrovalpage');
Route::post('/approvepage','HrController@approvepage');
Route::post('/rejectpage','HrController@rejectpage');


Route::get('/leaccept','HrController@leaccept');
Route::get('/lereject','HrController@lereject');
Route::post('/plots_add','PlotController@savePlots');

Route::get('/plots','PlotController@Plots');

Route::get('/plots_dailyslots','PlotController@plotsDailyslots');

Route::get('/viewplotdetails','PlotController@viewplotdetails');

Route::get('/sales_reports','SalesReportController@index');

Route::POST('/sal_reports','SalesReportController@index');
Route::post('/adddeliverydetails','HrController@adddeliverydetails');
Route::post('/assignunupdateproject','HrController@assignunupdateproject');
Route::get('/enquiryassign','NewdevlopController@enquiryassign');
Route::POST('/categoryenquiry','NewdevlopController@categoryenquiry');
Route::get('/getassignenquiry','NewdevlopController@getassignenquiry');
Route::get('/Categorywisecustomers','NewdevlopController@Categorywisecustomers');
Route::post('/storecustomerenq','NewdevlopController@storecustomerenq');
Route::get('/getcustomerenqlist','NewdevlopController@getcustomerenqlist');
Route::get('/Assignfollowup','NewdevlopController@Assignfollowup');
Route::POST('/resetwards','NewdevlopController@resetwards');
Route::get('/SMSSentreport','NewdevlopController@SMSSentreport');
Route::get('/invoicegen','NewdevlopController@invoicegen');
Route::get('/multisupplier','NewdevlopController@multisupplier');

Route::get('/getgstcal','NewdevlopController@getgstcal');

Route::POST('/sdhg','NewdevlopController@storeinvoice');
Route::POST('/assignunupdatemanu','NewdevlopController@assignunupdatemanu');

Route::get('/targetrem','NewdevlopController@targetrem');
Route::POST('/salestarget','NewdevlopController@salestarget');
Route::POST('/cattarget','NewdevlopController@cattarget');
Route::POST('/decicatedcust','NewdevlopController@decicatedcust');

Route::get('/assigncustomers','NewdevlopController@assigncustomers');
Route::POST('/customerstore','NewdevlopController@customerstore');
Route::get('/customervisit','NewdevlopController@customervisit');
Route::POST('/test','NewdevlopController@customerfeedback');
Route::POST('/testpull','NewdevlopController@visithistory');

Route::get('/assignvistedcustomer','NewdevlopController@assignvistedcustomer');

Route::get('/customerledger','NewdevlopController@customerledger');

Route::get('/customerbank','NewdevlopController@getbanktrans');

Route::POST('/testbank','NewdevlopController@testbank');
Route::get('/gethelper','NewdevlopController@gethelper');

Route::POST('/gettotaldetails','NewdevlopController@gettotaldetails');

Route::get('/test','AdminController@pnumbers');
Route::get('/test101','AdminController@mnumbers');

Route::get('/newcustomerassign','NewdevlopController@newcustomerassign');

Route::POST('/storenewcustomer','NewdevlopController@storenewcustomer');

Route::get('/dCustomers','NewdevlopController@dCustomers');

Route::get('/dnumbers','NewdevlopController@dnumbers');

Route::get('/test','AdminController@pnumbers');


Route::get('/dprojects','NewdevlopController@dprojects');
Route::get('/dmanus','NewdevlopController@dmanus');

Route::get('/denquery','NewdevlopController@denquery');

Route::POST('/getprojectsmap','NewdevlopController@getprojectsmap');
Route::get('/usercustomers','NewdevlopController@usercustomers');
Route::get('/Materialhub','NewdevlopController@Materialhub');
Route::get('/Retailers','RetailerController@Retailers');

Route::POST('/addmatirial','NewdevlopController@addmatirial');
Route::get('/matirialslot','NewdevlopController@matirialslot');

Route::POST('/updatematirial','NewdevlopController@updatematirial');

Route::get('/editmat','NewdevlopController@editmat');
Route::get('/editretailer','RetailerController@editretailer');

Route::get('/rejectinvoice','NewdevlopController@rejectinvoice');

Route::get('/deleteprice','NewdevlopController@deleteprice');

Route::POST('/addretail','RetailerController@addretail');

Route::get('/retailerslot','RetailerController@retailerslot');

 Route::POST('/updateretail','RetailerController@updateretail');

 Route::get('/teamleads','NewdevlopController@teamleads');
 Route::POST('/mreway','NewdevlopController@mreway');
 Route::POST('/mixedeway','NewdevlopController@mixedeway');
 Route::get('/multiplesuplier','NewdevlopController@multiplesuplier');
 Route::get('/trasportinvoice','NewdevlopController@trasportinvoice');

 Route::POST('/multisdhg','NewdevlopController@multisdhg');
 Route::POST('/multisdhgs','NewdevlopController@multisdhgs');

Route::get('/resetcustomer','NewdevlopController@resetcustomer');

Route::get('/updatereport','NewdevlopController@updatereport');
Route::get('/usingbrand','NewdevlopController@usingbrand');
Route::get('/testdistance','RetailerController@testdistance');
Route::get('/getmanudistance','RetailerController@getmanudistance');


Route::get('/unverifiedmanu','RetailerController@unverifiedmanu');
Route::get('/materialhubmap','RetailerController@materialhubmap');

Route::get('/petrol','RetailerController@petrol');
Route::get('/petrolapprove','RetailerController@petrolapprove');

Route::get('/petrolpaymentdone','RetailerController@petrolpaymentdone');

Route::get('/totalreport','RetailerController@totalreport');
Route::POST('/resetmultiinvoice','RetailerController@resetmultiinvoice');
Route::get('/attendence','RetailerController@attendence');

Route::POST('/attendenceapproval','RetailerController@attendenceapproval');
Route::get('/hrverify','RetailerController@hrverify');
Route::POST('/hrapproved','RetailerController@hrapproved');

 Route::get('/attendencereport','RetailerController@attendencereport');

 Route::get('/deletemultiplesuplier','RetailerController@deletemultiplesuplier');
Route::get('/customerfulldetails','RetailerController@customerfulldetails');
  
Route::get('/deleteyes','RetailerController@deleteyes');

  Route::get('/updatemonthly','RetailerController@updatemonthly');
  Route::get('/monthlyinvoicedata','RetailerController@monthlyinvoicedata');
  Route::get('/tpwithuser','RetailerController@tpwithuser');

  Route::get('/getmonthreport','RetailerController@getmonthreport');
  Route::POST('/editinvoicedata','RetailerController@editinvoicedata');
  Route::POST('/getcustomerids','RetailerController@getcustomerid');
  Route::get('/getsuperdetails','RetailerController@getsuperdetails');

Route::get('/blocked','AdminController@blocked');
Route::get('/manublocked','AdminController@manublocked');

Route::get('/projectupdatereport','AdminController@projectupdatereport');

Route::get('/manuupdatereport','AdminController@manuupdatereport');

Route::get('/totalcallattend','AdminController@totalcallattend');
Route::get('/projects','AdminController@projects');
Route::get('/manufactured','AdminController@manufactured');




Route::POST('/storeproposedprojects','AdminController@storeproposedprojects');
Route::POST('/storeproposedmanu','AdminController@storeproposedmanu');

Route::get('/deleteproposedprojects','AdminController@deleteproposedprojects');
Route::POST('/setgradeproject','AdminController@setgradeproject');
Route::POST('/setgrademanu','AdminController@setgrademanu');



Route::get('/thismonth','AdminController@bosstest');

Route::get('/customer_report','AdminController@customer_report');
Route::get('/customersalesreport','AdminController@customersalesreport');

Route::get('/monthlycustomerdeatils','AdminController@monthlydetails');

Route::get('/activecustomers','AdminController@activecustomers');
Route::get('/assignppids','AdminController@assignppids');
Route::get('/assignclosedcontractors','SalesController@assignclosedcontractors');
Route::get('/assignmpids','AdminController@assignmpids');
Route::POST('/getcustomerremarks','AdminController@getcustomerremarks');
Route::POST('/addtransport','AdminController@addtransport');
Route::get('/cancelMr','AdminController@cancelMr');
Route::get('/dedicatedwhatsapp','AdminController@dedicatedwhatsapp');

Route::get('/getmonthtpwithuserreport','AdminController@getmonthtpwithuserreport');

Route::get('/addotherexpensetoorder','AdminController@addotherexpensetoorder');

Route::get('/deletedelivery','HrController@deletedelivery');

Route::get('/cancelMr','AdminController@cancelMr');
Route::POST('/savemrunitprice','AdminController@savemrunitprice');
Route::POST('/getcustomerremarks','AdminController@getcustomerremarks');

Route::get('/dedicatedwhatsapp','AdminController@dedicatedwhatsapp');

Route::get('/Proposedprojectswhatsapp','AdminController@Proposedprojectswhatsapp');

Route::get('/tototallog','SalesController@tototallog');
Route::get('/vcard','SalesController@vcard');
Route::get('/manuvcard','SalesController@manuvcard');
Route::get('/pendingvendor','SalesController@pendingvendor');
Route::get('/Vendoraccept','SalesController@Vendoraccept');
Route::get('/Vendorreject','SalesController@Vendorreject');
Route::get('/cvcard','SalesController@cvcard');

Route::get('/newattend','SalesController@newattend');

Route::get('/getcustomername','SalesController@getcustomername');

Route::get('/projectmanuppid','SalesController@projectmanuppid');


Route::get('/closedcontractor','SalesController@closedcontractor');
Route::POST('/storecontractors','SalesController@storecontractors');
Route::get('/assignclosedcontractors','SalesController@assignclosedcontractors');

Route::get('/csite','SalesController@csite');
Route::get('/cbuilders','SalesController@cbuilders');
Route::get('/cowners','SalesController@cowners');


Route::get('/assignedclosedcustomers','SalesController@assignedclosedcustomers');

Route::get('/deleteassignedclosecustomer','SalesController@deleteassignedclosecustomer');

Route::get('/customerstatus','SalesController@customerstatus');




Route::get('/Holdingproposed','SalesController@Holdingproposed');
Route::get('/removeproposed','SalesController@removeproposed');
Route::get('/holdingcust','AdminController@holdingcust');
Route::get('/cash','SalesController@cashrecipt');
Route::POST('/storecash','SalesController@storecash');

Route::get('/downloadcash','SalesController@downloadcash')->name('downloadcash');
Route::get('/cancelcash','SalesController@cancelcash');



?>
