<html>
    <head>
        <link rel="stylesheet" type="text/css" href="nice.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" />
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <!-- Latest compiled JavaScript -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
    </head>
    <?php
        //插入連線資料庫的相關資訊
        require_once 'connectvars.php';
        //開啟一個會話
        $error_msg = "";
        session_start();
        //如果使用者未登入，即未設定$_SESSION['user_id']時，執行以下程式碼
        if(isset($_SESSION['user_id'])==false){
            if(isset($_POST['submit'])){//使用者提交登入表單時執行如下程式碼
                $dbc = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
                $user_username = mysqli_real_escape_string($dbc,trim($_POST['username']));
                $user_password = mysqli_real_escape_string($dbc,trim($_POST['password']));
                if($user_username!=""&&$user_password!=""){
                    //MySql中的SHA()函式用於對字串進行單向加密
                    $query = "SELECT id, name FROM user WHERE name = '".$user_username."' AND password = '".$user_password."'";
                    //用使用者名稱和密碼進行查詢
                    $data = mysqli_query($dbc,$query);
                    //若查到的記錄正好為一條，則設定SESSION，同時進行頁面重定向
                    if (!empty($data)){
                        if(mysqli_num_rows($data)==1){
                            $row = mysqli_fetch_array($data);
                            $_SESSION['user_id']=$row['id'];
                            $_SESSION['username']=$row['name'];
                            $home_url = 'loged.php';
                            header('Location: '.$home_url);
                        }else{//若查到的記錄不對，則設定錯誤資訊
                            $error_msg = 'Sorry, you must enter a valid username and password to log in.';
                        }
                    }
                    else{//若查到的記錄不對，則設定錯誤資訊
                            $error_msg = 'Sorry, you must enter a valid username and password to log in.';
                    }
                }else{
                    $error_msg = 'Sorry, you must enter a valid username and password to log in.';
                }
            }
        }
        else{//如果使用者已經登入，則直接跳轉到已經登入頁面
            $home_url = 'loged.php';
            header('Location: '.$home_url);
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
                      <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit" name="submit">Sign in</button>
                    </form>
                    <button class="btn btn-lg btn-primary btn-block text-uppercase" onclick="location.href='http://localhost/iot/createAC.php'" type="button">Create Account</button>
                    <?php
                        if(!isset($_SESSION['user_id'])){
                            echo $error_msg;
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