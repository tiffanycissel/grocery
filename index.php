<?php 
include('includes/header.html');
require_once('assets/dbconn.php');
require_once('assets/functConfig.php');

include_once('includes/zipArray.php');

if($_SERVER['REQUEST_METHOD']!=='POST'){
    if(isset($_GET['task'])){
        if ($_GET['task']=='complete-registration'){
            include('views/regSignIn.php');
        } else if ($_GET['task']=='reset'){
            include('views/resetProc.php');
        }
    }
    else if(!isset($_GET['page'])||$_GET['page']=='home'){
        include('views/home.html');
    } else if ($_GET['page']=='signin') {
        include('views/signin.html');
    } else if ($_GET['page']=='register'){
        include('views/register.html');
    } else if ($_GET['page']=='search'){
        include('views/search.php');   
    } else if ($_GET['page']=='settings'){
        include('views/settings.php');
    } else if ($_GET['page']=='favorites'){
        include('views/favorites.php');
    } else if ($_GET['page']=='updates'){
        include('views/updates.html');
    } else if ($_GET['page']=='feedback'){
        include('views/feedbackForm.html');
    }
} else {
    if ($_POST['task']==='signin'){
        include('includes/signin.php');
    } else if ($_POST['task']==='register'){
        include('includes/register.php');
    } else if ($_POST['task']==='profile'){
        include('views/profile.php');
    } else if ($_POST['task']==='search-form-sub'){
        if(!empty($_POST['matchID'])){
            include('includes/searchProcess.php');
        } else {
            include('includes/searchTerms.php');
        }
        
    } else if ($_POST['task']==='updateItem'){
        if(isset($_SESSION['userID'])){
            include('views/updateItem.html');
        } else {
            $_SESSION['holdUpdateVals'] = true;
            include('views/signin.html');
        }
    } else if($_POST['task']==='brand-match-select'){
        include('includes/brandSelectProcess.php');
    } else if ($_POST['task']==='showAllBrands'){
        include('includes/showAllBrandsProcess.php');
    } else if ($_POST['task']==='submitUpdate'){
        include('includes/submitUpdate.php');
    } else if ($_POST['task']==='sendMail'){
        include('includes/sendMail.php');
    } else if ($_POST['task']==='forgotPW'){
        include('views/forgotPW.php');
    } else if ($_POST['task']==='resetPW'){
        //function or include that carries out DB action and then includes email submission
        include('includes/resetRequest.php');
    // } else if ($_POST['task']==='fdbkSub'){
    //     include('includes/feedbackSub.php');
    } else if ($_POST['task']==='resetSub'){
        include('includes/resetSub.php');
    }
}

include('includes/footer.html');?>