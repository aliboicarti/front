<?php
// Check if this is an API request
if (isset($_GET['endpoint']) && $_GET['endpoint'] === 'orders' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    ob_clean();
    header("Content-Type: application/json; charset=UTF-8");
    
    // Database configuration
    $host = "localhost";
    $dbname = "my_website";
    $username = "dev"; 
    $password = "";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($data['name']) || !isset($data['phone']) || !isset($data['description'])) {
            http_response_code(400);
            echo json_encode(["error" => "Name, phone, and description are required"]);
            exit;
        }
        
        $stmt = $pdo->prepare("INSERT INTO orders (name, phone, address, email, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['name'],
            $data['phone'],
            $data['address'] ?? null,
            $data['email'] ?? null,
            $data['description']
        ]);
        
        http_response_code(201);
        echo json_encode([
            "id" => $pdo->lastInsertId(),
            "message" => "Order created successfully"
        ]);
        exit;
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => "Database error"]);
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NorthStarWin|خدمات در و پنجره pvc </title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.3.0/material.indigo-pink.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/home.css">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <header>
        <nav>
            <ul>
                <a style="color: #1d1d1d;" href="">شبکه های اجتماعی<span>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_3_69)">
                    <path d="M20.5 10L21 8H17L18 4H16L15 8H11L12 4H10L9 8H5L4.5 10H8.5L7.5 14H3.5L3 16H7L6 20H8L9 16H13L12 20H14L15 16H19L19.5 14H15.5L16.5 10H20.5ZM13.5 14H9.5L10.5 10H14.5L13.5 14Z" fill="#0275CA"/>
                    </g>
                    <defs>
                    <clipPath id="clip0_3_69">
                    <rect width="24" height="24" fill="white"/>
                    </clipPath>
                    </defs>
                    </svg>
                </span></a>

                <a style="color: #1d1d1d;" href="">تایم کاری : هر روز هفته<span>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_3_82)">
                    <path d="M11.99 2C6.47 2 2 6.48 2 12C2 17.52 6.47 22 11.99 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 11.99 2ZM12 20C7.58 20 4 16.42 4 12C4 7.58 7.58 4 12 4C16.42 4 20 7.58 20 12C20 16.42 16.42 20 12 20Z" fill="#0275CA"/>
                    <path d="M12.5 7H11V13L16.25 16.15L17 14.92L12.5 12.25V7Z" fill="#0275CA"/>
                    </g><defs>
                    <clipPath id="clip0_3_82">
                    <rect width="24" height="24" fill="white"/>
                    </clipPath>
                    </defs>
                    </svg>
                </span></a>

                <a style="text-decoration: line-through; color: rgb(51, 139, 255);" href="">کار ما چیه؟</a>
            </ul>
        </nav>
        <nav>
            <ul>
                <a style="color: #1d1d1d;" href=""><span>
                    <svg width="27" height="27" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_3_95)">
                    <path d="M21.0008 13.0379C19.2608 16.4575 16.4575 19.2487 13.0379 21.0008L10.3796 18.3425C10.0533 18.0163 9.57 17.9075 9.14708 18.0525C7.79375 18.4996 6.33167 18.7413 4.83333 18.7413C4.16875 18.7413 3.625 19.285 3.625 19.9496V24.1667C3.625 24.8313 4.16875 25.375 4.83333 25.375C16.1796 25.375 25.375 16.1796 25.375 4.83333C25.375 4.16875 24.8313 3.625 24.1667 3.625H19.9375C19.2729 3.625 18.7292 4.16875 18.7292 4.83333C18.7292 6.34375 18.4875 7.79375 18.0404 9.14708C17.9075 9.57 18.0042 10.0413 18.3425 10.3796L21.0008 13.0379Z" fill="#0275CA"/>
                    </g>
                    <defs>
                    <clipPath id="clip0_3_95">
                    <rect width="29" height="29" fill="white" transform="matrix(-1 0 0 1 29 0)"/>
                    </clipPath>
                    </defs>
                    </svg>
                </span>+98922 00 23 242</a>
                <a href="" class="other-nums">دیگر شماره ها</a>
                <a href="" class="news">اخبار<div class="drop-shape"></div></a>
            </ul>
        </nav>
    </header>

    <section id="section1">
        <div class="container">
            <div class="right-side">
                <div class="img-right"></div><h1 class="hr">لورم اسپیسام</h1>
                <p class="pr">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از  طراحان گرافیک است، چاپگرها و متون بلکه </p>
                <div class="button-right">اطلاعات بیشتر<span> -> </span></div>
            </div>

            <div class="separator">
                <div class="logo"></div>
                <div class="line"></div>
                <div class="end"></div>
            </div>

            <div class="left-side">
                <h1 class="hl">لورم اسپیسام</h1>
                <div class="img-left"></div>
                <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از  طراحان گرافیک است، چاپگرها و متون بلکه </p>
                <div class="button-left">اطلاعات بیشتر<span> -> </span></div>
            </div>
        </div>


        <div class="form">
        <div class="form-wrapper">
            <div class="form-header">
                <h1>فرم سفارش</h1>
            </div>
            
            <!-- Step 1: اطلاعات تماس -->
            <div class="step active" id="step1">
                <div class="grid-container">
                    <div class="grid-column">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="name" required>
                            <label class="mdl-textfield__label" for="name">نام و نام خانوادگی *</label>
                        </div>

                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="address">
                            <label class="mdl-textfield__label" for="address">آدرس <span class="optional-badge">اختیاری</span></label>
                        </div>
                    </div>

                    <div class="grid-column">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="tel" id="phone" required>
                            <label class="mdl-textfield__label" for="phone">شماره تماس *</label>
                        </div>

                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="email" id="email">
                            <label class="mdl-textfield__label" for="email">ایمیل <span class="optional-badge">اختیاری</span></label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: توضیحات -->
            <div class="step" id="step2">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <textarea class="mdl-textfield__input" type="text" rows="6" id="description" required></textarea>
                    <label class="mdl-textfield__label" for="description">توضیحات سفارش *</label>
                </div>
            </div>

            <!-- پیام موفقیت -->
            <div class="success-message" id="successMessage">
                <div class="success-icon">
                    <i class="material-icons">check_circle</i>
                </div>
                <h2>سفارش شما با موفقیت ثبت شد!</h2>
                <p>از اعتماد شما سپاسگزاریم. به زودی با شما تماس خواهیم گرفت.</p>
            </div>
        </div>

        <!-- FAB Button -->
        <button class="fab-button" id="fabButton" onclick="handleFabClick()">
            <i class="material-icons" id="fabIcon">arrow_back</i>
        </button>
    </div>
    </section>

    
    <svg class="dec1" width="139" height="171" viewBox="0 0 139 171" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect x="-92.5858" y="92.7817" width="108" height="108" transform="rotate(-45 -92.5858 92.7817)" stroke="#D8D8D8" stroke-width="2"/>
        <rect x="-15.5858" y="-13.2183" width="108" height="108" transform="rotate(-45 -15.5858 -13.2183)" stroke="#D8D8D8" stroke-width="2"/>
        <rect x="10" y="115.188" width="37.0355" height="37.0355" transform="rotate(-45 10 115.188)" fill="#84C0EB" fill-opacity="0.31"/>
        <rect x="16" y="132.782" width="25.1477" height="25.1477" transform="rotate(-45 16 132.782)" fill="#005492" fill-opacity="0.7"/>
    </svg>
    <svg class="dec2" width="74" height="176" viewBox="0 0 74 176" fill="none" xmlns="http://www.w3.org/2000/svg">
    <rect x="1.41421" y="62.2254" width="86" height="86" transform="rotate(-45 1.41421 62.2254)" stroke="#D8D8D8" stroke-width="2"/>
    <rect x="1.61526" y="113.652" width="86" height="86" transform="rotate(-45 1.61526 113.652)" stroke="#D8D8D8" stroke-width="2"/>
    </svg>
    <svg class="dec3" width="63" height="58" viewBox="0 0 63 58" fill="none" xmlns="http://www.w3.org/2000/svg">
    <rect y="31.1515" width="31.5243" height="31.5243" transform="rotate(-81.1804 0 31.1515)" fill="#4D9EDA" fill-opacity="0.31"/>
    <rect x="41.0705" y="53.7708" width="18.3464" height="18.3464" transform="rotate(-79.1751 41.0705 53.7708)" fill="#005492"/>
    </svg>
    <svg class="dec4" width="296" height="391" viewBox="0 0 296 391" fill="none" xmlns="http://www.w3.org/2000/svg">
    <rect x="16.2471" y="261.449" width="55.1601" height="55.1601" transform="rotate(-45.4086 16.2471 261.449)" fill="#4D9EDA" fill-opacity="0.31"/>
    <rect x="297.313" y="1.41421" width="148" height="148" transform="rotate(45 297.313 1.41421)" stroke="#E3E3E3" stroke-width="2"/>
    <rect x="221.313" y="179.414" width="148" height="148" transform="rotate(45 221.313 179.414)" stroke="#E3E3E3" stroke-width="2"/>
    <rect y="260.756" width="39.8058" height="39.8058" transform="rotate(-45.4086 0 260.756)" fill="#005899"/>
    </svg>
    <svg class="dec5" width="241" height="356" viewBox="0 0 241 356" fill="none" xmlns="http://www.w3.org/2000/svg">
    <rect x="-34.2685" y="97.5807" width="115" height="115" transform="rotate(45 -34.2685 97.5807)" stroke="#E3E3E3" stroke-width="2"/>
    <rect x="61.191" y="191.626" width="115" height="115" transform="rotate(45 61.191 191.626)" stroke="#E3E3E3" stroke-width="2"/>
    <rect x="61.898" y="1.41421" width="115" height="115" transform="rotate(45 61.898 1.41421)" stroke="#E3E3E3" stroke-width="2"/>
    <rect x="157.357" y="95.4594" width="115" height="115" transform="rotate(45 157.357 95.4594)" stroke="#E3E3E3" stroke-width="2"/>
    <g filter="url(#filter0_f_1_217)">
    <rect x="-26" y="177.431" width="116.387" height="116.387" transform="rotate(-43.7144 -26 177.431)" fill="#0275CA" fill-opacity="0.6"/>
    </g>
    <defs>
    <filter id="filter0_f_1_217" x="-74.9" y="48.0999" width="262.355" height="262.355" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
    <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"/>
    <feGaussianBlur stdDeviation="24.45" result="effect1_foregroundBlur_1_217"/>
    </filter>
    </defs>
    </svg>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.3.0/material.min.js"></script>
    <script src="../js/spt.js" ></script>

</body>
</html>