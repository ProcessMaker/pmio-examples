<script type="application/javascript">
    document.body.style.backgroundColor = "<?php echo $bgcolor;?>";

</script>
<style>
    .nav-tabs > li, .nav-pills > li {
        float:none;
        display:inline-block;
        *display:inline; /* ie7 fix */
        zoom:1; /* hasLayout ie7 trigger */
    }

    .nav-tabs, .nav-pills {
        text-align:center;
        height: 45px;
    }
</style>
<!-- Nav tabs -->
<a href="/"><img alt="PM Core" height="45" src="http://www.processmaker.com/sites/all/themes/pmthemev2/img/white-badge.png" style="position: absolute;">
    </a>
<ul id="myTabs" class="nav nav-tabs" role="tablist">

    <li role="presentation" class="mvp"><a href="#mvp" aria-controls="nda" role="tab" data-toggle="tab">MVP example</a></li>
    <li role="presentation"><a href="#nda" aria-controls="nda" role="tab" data-toggle="tab">NDA example</a></li>

</ul>

<div class="tab-content">
    <div role="tabpanel" class="tab-pane" id="mvp">
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
          <li><img align="right" height="50" src="<?php echo $avatar;?>"></li>
          <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $user;?> <span class="caret"></span></a>
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
        </div>
    <div role="tabpanel" class="tab-pane" id="nda">
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

                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">

                        <li<?php echo (preg_match('/import_form.html/',$_SERVER['REQUEST_URI'])?' class="active"':'');?>><a href="/import_form.html?example=nda">Import</a></li>
                        <li<?php echo (preg_match('/processes_form.html/',$_SERVER['REQUEST_URI'])?' class="active"':'');?>><a href="processes_form.html?example=nda">Process List</a></li>
                        <li><a target="_new" href="https://docs.google.com/a/processmaker.com/forms/d/e/1FAIpQLSeCpy4wYwvLkSjUJIAr-VHjsquKS9SXlAhIMokmYU0MqtuPTQ/viewform">Manual Request (google form)</a></li>

                        <li<?php echo (preg_match('/check_nda_status.html/',$_SERVER['REQUEST_URI'])?' class="active"':'');?>><a href="check_nda_status.html?example=nda">Check NDA status </a></li>

                    </ul>

                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
    </div>
</div>
<script type="text/javascript">
    $('ul#myTabs li a').on('click', function (e) {
        var link = $(this).attr('href');
        if (link == '#nda') window.location.replace('http://'+window.location.host+'?example=nda&user=Test');
        else if (link == '#mvp') window.location.replace('http://'+window.location.host);
    });

    <?php if (isset($_GET['example']) && $_GET['example'] == 'nda') {
        echo '$(\'#myTabs a[href="#nda"]\').tab(\'show\');';

    } else
        echo'$(\'#myTabs a[href="#mvp"]\').tab(\'show\');';
         ?>
</script>