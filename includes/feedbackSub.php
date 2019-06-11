<?php
include('sendMail.php');


$email = inputValiScrub($_POST['email'],'email');
$subject = $_POST['subj'];
$msg = htmlspecialchars($_POST['textInput']);

$feedbackInfo = ['email'=>$email, 'fdbkSub'=>$subject, 'fdbkMsg'=>$msg, 'topic'=>'', 'siteRoot'=>$siteRoot,'time'=>$_POST['theTime']];

$topics = ['fdbkNotice','fdbk'];

foreach($topics as $topic){
    $feedbackInfo['topic']=$topic;
    sendTheEmail($feedbackInfo);
}