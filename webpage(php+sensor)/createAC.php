<html>
    <head>
        <link rel="stylesheet" type="text/css" href="nice.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <!-- Latest compiled JavaScript -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
    </head>
    <?php session_start();?>
    <?php
        //插入連線資料庫的相關資訊
        require_once 'connectvars.php';
        //開啟一個會話
        $error_msg = "";
        $create_msg ="";
        //如果使用者未登入，即未設定$_SESSION['user_id']時，執行以下程式碼
            if(isset($_POST['submit'])){//使用者提交登入表單時執行如下程式碼
                $dbc = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
                $user_username = mysqli_real_escape_string($dbc,trim($_POST['username']));
                $user_password = mysqli_real_escape_string($dbc,trim($_POST['password']));
                if($user_username!=""&&$user_password!=""){
                    //MySql中的SHA()函式用於對字串進行單向加密
                    $query = "INSERT INTO `user`(`name`, `password`) VALUES ('".$user_username."','".$user_password."')";
                    //用使用者名稱和密碼進行查詢
                    $data = mysqli_query($dbc,$query);
                    $create_msg = 'create account successful.';
                    if($data === false){
                        $error_msg = 'username already used.';
                        $create_msg = null;
                    }
                    //若查到的記錄正好為一條，則設定SESSION，同時進行頁面重定向
                }
                else{
                    $error_msg = 'Sorry, you must enter username and password to Create Account.'; 
                }
            }
    ?>
    <body>
        <div class="container">
            <div class="row">
              <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                <div class="card card-signin my-5">
                  <div class="card-body">
                    <h5 class="card-title text-center">Sign In</h5>
                    <form class="form-signin" method = "post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                      <div class="form-label-group">
                        <input type="text" id="username" class="form-control" name="username" placeholder="username" value="<?php if(!empty($user_username)) echo $user_username; ?>" required autofocus>
                        <label for="username">username</label>
                      </div>

                      <div class="form-label-group">
                        <input type="password" id="password" class="form-control" name="password" placeholder="Password" required>
                        <label for="password">Password</label>
                      </div>
                      <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit" name="submit">Create Account</button>
                    </form>
                    <button class="btn btn-lg btn-primary btn-block text-uppercase" onclick="location.href='http://localhost/iot/index.php'" type="button">SIGN IN PAGE</button>
                    <?php
                        if($error_msg != ""){
                            echo $error_msg;
                        }
                        if($create_msg != ""){
                            echo $create_msg;
                        }
                    ?>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <!--通過$_SESSION['user_id']進行判斷，如果使用者未登入，則顯示登入表單，讓使用者輸入使用者名稱和密碼-->
    </body>
</html>