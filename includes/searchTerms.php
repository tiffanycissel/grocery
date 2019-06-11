<h1>Search Term Matches</h1>
<main id="searchTerms">
<?php
include('./assets/ajax.php');

$theTerm = $_POST['searchTerm'];
$theTermResults = getSearchTerms2($conn,$theTerm);
$sendingPage = 'searchTerms';
?>

<h2>Results for <span class="italic"><?php echo $theTerm; ?></span></h2>
<form class="search" id="search-form" method="post" action="index.php">    
    <input type="hidden" name="formType" value="search">
    <input type="hidden" name="task" value="search-form-sub">
    <input type="hidden" name="matchID" id="matchID">
    <input type="hidden" name="matchType" id="matchType">
    <input type="hidden" name="user_zip" value="<?php echo $_POST['user_zip']; ?>">  
    <input type="hidden" name="searchTerm" value="<?php echo $theTerm; ?>">
</form>

<ul>

<?php 
foreach ($theTermResults as $key => $value) {
    echo '<li><a href="#" data-match-id="'.$theTermResults[$key]['id'].'" data-match-type="'.$theTermResults[$key]['type'].'">'.$theTermResults[$key]['name'].'</a></li>';
}
?>

</ul>

</main>