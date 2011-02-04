<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="shortcut icon" href=<?php echo(base_url() . "styles/images/favicon.ico")?> />
    
    <link rel="stylesheet" type="text/css" href=<?php echo(base_url() . "styles/main.css")?> />
    <link rel="stylesheet" type="text/css" href=<?php echo(base_url() . "styles/form.css")?> />

    <title>Daya Bay Database Login</title>	
</head>

<body>
    <div id="content">
        <h5 style="font-weight: bold;">
            <a href="http://dayabay.ihep.ac.cn/ODM/index.php/dybdb">IHEP</a>
            &ensp;&ensp;|&ensp;&ensp;
            <a href="http://portal.nersc.gov/project/dayabay/dybruns/dybdb">NERSC</a>
        </h5>
        
        <div id="login">

            
            <h1>
                Login
            </h1>
            
            <table id='login_table' cellpadding="0" cellspacing="0" border="0" align="right" style="width:100%">
            <?php echo form_open('dybdb/login'); ?>

                <tr>
                    <td><h5>Username</h5></td>
                    <td>
                        <input type="text" name="username" id="username" value="<?php echo set_value('username'); ?>" size="50" />
                    </td>
                </tr>

                <tr>
                    <td><h5>Password</h5></td>
                    <td>
                        <input type="password" name="password" id="password" value="" size="50" />
                    </td>
                </tr>

                <tr>
                    <td><h5>Database</h5></td>
                    <td>
                        <select name="select_db" id='select_db'>
                            <option value="lbl">LBL</option>
                            <option value="ihep">IHEP</option>
                            <option value="dayabay">Central Database</option>
                        </select>
                    </td>
                </tr>

            </table>
            
            <p><input type="submit" value="Log in" id="login_submit" class='button' /></p>
            <?php echo form_close();?>
        </div>

        <p>
            <span style="color:red"><?php echo $login_errors; ?></span>
            <?php echo form_error('username'); ?>
            <?php echo form_error('password'); ?>
        </p>
                
    </div>	
    
    <script type="text/javascript">
    var select_db = document.getElementById("select_db");
    if(window.location.href.substr(0,30).indexOf('ihep') > 0) {
        select_db.value = "ihep";
    }
    else {
        select_db.value = "lbl";
    }
    
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-577038-2']);
      _gaq.push(['_trackPageview']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
      
      

      
      
    </script>

</body>
</html>