<?php
session_start();
$current_page = 'Subscription';
include_once('./template/header.php');
include_once('./template/navbar.php');
?>

<script type="text/javascript">
$(function()
  {
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


<div class="container">

    <!-- Main component for a primary marketing message or call to action -->

    <div class="controls">
        <label>Subscribe to Our Newsletter!</label>
        <form id="newsletter" role="form" autocomplete="off">

        <button type="submit" class="btn btn-primary btn-block">Submit</button>
        <p></p>

        <div class="entry input-group">
            <input class="form-control" name="fields[]" type="text" placeholder="email address" />
            <span class="input-group-btn">
            <button class="btn btn-success btn-add" type="button">
            <span class="glyphicon glyphicon-plus"></span>
            </button>
            </span>
        </div>

        <p></p>

        </form>
        <div id="response"></div>
        <br>
        <small>Press <span class="glyphicon glyphicon-plus gs"></span> to add another form field :)</small>
    </div>

</div> <!-- /container -->



<?php
    include_once('./template/footer.php');
    ?>


</body></html>

