<?php
// jwt_config.php

// Key bảo mật dùng để mã hóa và giải mã JWT
define('JWT_SECRET_KEY', 'your_secret_key_here');  // Thay 'your_secret_key_here' bằng một key bảo mật mạnh
define('JWT_ALGORITHM', 'HS256');  // Thuật toán mã hóa (HS256 là thuật toán phổ biến)
