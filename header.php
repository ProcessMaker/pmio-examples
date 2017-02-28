<script type="application/javascript">
    document.body.style.backgroundColor = "<?php echo $bgcolor;?>";
</script>

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php"><img alt="PM Core" height="22" src="http://www.processmaker.com/sites/all/themes/pmthemev2/img/white-badge.png"></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">

<li<?php echo (preg_match('/import_form.html/',$_SERVER['REQUEST_URI'])?' class="active"':'');?>><a href="/import_form.html">Import</a></li>
<li<?php echo (preg_match('/processes_form.html/',$_SERVER['REQUEST_URI'])?' class="active"':'');?>><a href="processes_form.html">Process List</a></li>
<li<?php echo (preg_match('/manual_request.html/',$_SERVER['REQUEST_URI'])?' class="active"':'');?>><a href="manual_request.html">Manual Request</a></li>
<li><a href="https://docs.google.com/a/processmaker.com/forms/d/e/1FAIpQLSeEqBZjYVXKIATk-p1enNJCtUgmVMYiZ4lFx9w5o_8kjJ5N4w/viewform" target="_new">Google Form</a></li>
<li<?php echo (preg_match('/record_customer.html/',$_SERVER['REQUEST_URI'])?' class="active"':'');?>><a href="record_customer.html">Record Customer</a></li>
<li<?php echo (preg_match('/validate_workspace.html/',$_SERVER['REQUEST_URI'])?' class="active"':'');?>><a href="validate_workspace.html">Validate Workspace</a></li>
<li<?php echo (preg_match('/verify_on_boarding_data.html/',$_SERVER['REQUEST_URI'])?' class="active"':'');?>><a href="verify_on_boarding_data.html">Verify Onboarding data</a></li>

      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#"><img height="32" src="<?php echo $avatar;?>"><?php echo $user;?></a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">User <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="?user=Test">Test</a></li>
            <li><a href="?user=Alice">Alice</a></li>
            <li><a href="?user=Bob">Bob</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>