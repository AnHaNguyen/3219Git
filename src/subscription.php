<?php
session_start();
$current_page = 'Subscription';

include_once('./template/header.php');
include_once('./template/navbar.php');
include_once('./php/controller.php');

if (isset($_POST["submit"])) {
        if(!empty($_POST['emails']) && isset($_SESSION['loginuser']) && !empty($_SESSION['loginuser'])) {
            foreach($_POST['emails'] as $check) {
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  					$message = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Invalid email format</div>'; 
				} else {
					$response = execute('addemail',null,$check,$_SESSION['loginuser'],null,null,'','');
					if(strcmp($response,"sucess")){
						$message = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Successfully subscribed!</div>';
					}
				}
            }
        }
    }
?>

<script type="text/javascript">
$(function() {
  $(document).on('click', '.btn-add', function(e)
                 {
                 e.preventDefault();
                 
                 var controlForm = $('.controls form:first'),
                 currentEntry = $(this).parents('.entry:first'),
                 newEntry = $(currentEntry.clone()).appendTo(controlForm);
                 
                 newEntry.find('input').val('');
                 controlForm.find('.entry:not(:last) .btn-add')
                 .removeClass('btn-add').addClass('btn-remove')
                 .removeClass('btn-success').addClass('btn-danger')
                 .html('<span class="glyphicon glyphicon-minus"></span>');
                 }).on('click', '.btn-remove', function(e)
                       {
                       $(this).parents('.entry:first').remove();
                       
                       e.preventDefault();
                       return false;
                       });
  });
</script>

<style>
.entry{
    margin-bottom: 20px;
}
</style>


<div class="container">

    <!-- Main component for a primary marketing message or call to action -->
	<div id="response">
		<?php echo $message;?>
	</div>
    
	<div class="controls">
        <label>Subscribe to Our Newsletter!</label>
         <form method="post" role="form" autocomplete="off" action="subscription.php" id="subscribe">
        	<input class="btn btn-primary btn-block" id="submit" name="submit" value="Submit" type="submit">
			<p></p>
			<div class="entry input-group">
				<input class="form-control" name="emails[]" type="text" placeholder="email address" />
				<span class="input-group-btn">
				<button class="btn btn-success btn-add" type="button">
				<span class="glyphicon glyphicon-plus"></span>
				</button>
				</span>
			</div>
        </form>
        <br>
        <small>Press <span class="glyphicon glyphicon-plus gs"></span> to add another form field :)</small>
    </div>

</div> <!-- /container -->



<?php
    include_once('./template/footer.php');
    ?>


</body></html>

