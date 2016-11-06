<?php
    session_start();
    $current_page = 'Num of Lines';
  
    include_once('./template/header.php');
    include_once('./template/navbar.php');
    include_once('./php/controller.php');
    
    if(isset($_SESSION['git_url']) && !empty($_SESSION['git_url']) && isset($_SESSION['git_username']) && !empty($_SESSION['git_username'])) {
        $result = execute('getlines', null, null, null, null, null,'','');
        $result = json_encode($result);
    }
 
    ?>

<link href="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet" />

<script src="./assets/js/d3pie.min.js"></script>
<script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<script src="./assets/js/app05.js" type="text/javascript"></script>

<style>
svg{
    width: 600px;
    position: relative;
    left: 50%;
    -webkit-transform: translateX(-50%);
    -ms-transform: translateX(-50%);
    transform: translateX(-50%);
}
</style>


<div class="container">

<div class="row">
	<h3><?php echo $_SESSION['git_url'] ?></h3>
    <div class="col-sm-12">
        <div id="chart"></div>
    </div>

    <div class="col-sm-12">
        <h4 class="sub-header">The following historical commit information, by author, was found.</h4>
        <br/>
        <div class="table-responsive">
            <table id="sortable" class="table table-striped">
                <thead>
                    <tr>
                        <th class="col-md-1">Author</th>
                        <th class="col-md-2">Line Numbers</th>
                    </tr>
                </thead>
                <tbody id="tablebody01"></tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    var jsonData = '<?php echo $result ?>';
	if(jsonData){
		var data = JSON.parse(jsonData);
		buildTable(data);
		$(document).ready(function() {
			$('#sortable').DataTable({
				"order": [[ 1, "desc" ]]
			});
		});
		drawGraph();
	}
</script>

</div> <!-- /container -->

</body></html>

