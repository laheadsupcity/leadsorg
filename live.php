<!DOCTYPE html>
<html lang="en">
<head>
<title>Scrapping</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style>
.active5{background:#337ab7!important;}
</style>
</head>
<body>
<div style="width:100%; float:left; margin:0;"><?php  include('nav.php'); ?></div>

<div class="scr1">
    <form action="livepropertydetail.php" method="post" >
<div id="pagebody" align="center" style="max-width: 1300px;">

            <div class="panel panel-primary" style="width: 100%; border:0;" align="left">
                <div class="panel-heading">
                    <div>PROPERTY ACTIVITY REPORT</div>
                </div>
                <div class="panel-body">
                    <div class="panel panel-danger">
                        <div class="panel-body">
                            <p>
                                The purpose of the Internet based Property Activity Report is to allow easy access
                        and visual display of general information from the Los Angeles Housing and Community
                        Investment Department (HCIDLA) Code and Rent Information System (CRIS). This general
                        information can be generated by the associated address(es), Assessor Parcel Number
                        (APN) or Case number.
                            </p>
                        </div>
                    </div>


                    <!-- <div class="BigFont">Please enter only one of the three possible search criteria:</div>


                        <br>
                        
                        <br>


                        <div>
                            <div class="col-md-3">
                                <label for="StreetNo" class="control-label ">Street Number:</label>
                            </div>

                            <div class="col-md-4">
                                <input name="streetno" type="text" id="MainContent_tbxStreetNo" class="form-control" placeholder="Street Number" >
                            </div>
                        </div>
                        <br>
                        <br>
                        <div>
                            <div class="col-md-3">
                                <label for="StreetName" class="control-label">Street Name:</label>
                            </div>
                            <div class="col-md-4">
                                <input name="streetname" type="text" id="MainContent_tbxStreetName" class="form-control" placeholder="Street Name" >
                            </div>
                        </div>
                        <br>
                        <br>
                        <br>-->
                        <div>
                            <div class="col-md-3">
                                <label for="APN" class="control-label">10-Digit Assessor Parcel Number (APN)<sup style='color:red'>*</sup>:</label>
                            </div>
                            <div class="col-md-4">
                                <input name="apn" type="text" id="MainContent_tbxAPN" class="form-control" placeholder="APN" required>

                            </div>
                        </div>
                    <br>
                    <br>
                    <br>
                    <!--<div>
                        <div class="col-md-3">
                            <label for="APN" class="control-label">Case Number:</label>
                        </div>
                        <div class="col-md-4">
                            <input name="ctl00$MainContent$tbxCasenum" type="text" id="MainContent_tbxCasenum" class="form-control" placeholder="Case Number">

                        </div>
                    </div> 
                        <br>
                        <br>-->
                        <div>
                            <div class="col-md-3">
                                <input type="submit" name="ctl00$MainContent$BtnSubmit" value="Submit" id="MainContent_BtnSubmit" class="btn btn-primary">
                            </div>
                             <div class="col-md-9"></div>
                        </div>


                </div>
            </div>



        </div>
    </form>
</div>

</body>
</html>

