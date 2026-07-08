<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mental Health Monitoring System')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Poppins',sans-serif;
        }

        :root{
            --primary:#2563EB;
            --primary-dark:#1E40AF;
            --success:#10B981;
            --danger:#EF4444;
            --warning:#F59E0B;
            --bg:#eef3fb;
            --glass:rgba(255,255,255,.72);
        }

        body{
            background:
                radial-gradient(circle at top left,#dbeafe 0%,transparent 40%),
                radial-gradient(circle at bottom right,#bfdbfe 0%,transparent 35%),
                var(--bg);
            min-height:100vh;
            color:#1e293b;
            overflow-x:hidden;
        }

        a{
            text-decoration:none;
        }

        /*=====================
            LOGIN
        =====================*/

        .auth-wrapper{
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            padding:30px;
            background:linear-gradient(135deg,#2563EB,#60A5FA);
        }

        .login-card{
            border:none;
            border-radius:28px;
            overflow:hidden;
            background:white;
            box-shadow:0 25px 60px rgba(0,0,0,.15);
        }

        .left-side{
            background:linear-gradient(180deg,#2563EB,#1D4ED8);
            color:white;
            display:flex;
            justify-content:center;
            align-items:center;
            flex-direction:column;
            text-align:center;
            padding:60px 45px;
        }

        .left-side i{
            font-size:85px;
            margin-bottom:20px;
        }

        .right-side{
            background:white;
            padding:55px;
        }

        .input-group-text{
            border-radius:14px 0 0 14px;
            background:white;
            border-right:none;
        }

        .form-control{
            border-left:none;
            border-radius:0 14px 14px 0;
            height:52px;
            box-shadow:none!important;
        }

        .btn-login{
            height:52px;
            border-radius:14px;
            font-weight:600;
            transition:.3s;
        }

        .btn-login:hover{
            transform:translateY(-2px);
            box-shadow:0 15px 30px rgba(37,99,235,.25);
        }

        /*=====================
            SIDEBAR
        =====================*/

        .sidebar{
            position:fixed;
            left:20px;
            top:20px;
            width:270px;
            height:calc(100vh-40px);

            background:linear-gradient(180deg,#2563EB,#1E40AF);

            backdrop-filter:blur(20px);

            border-radius:22px;

            padding:25px;

            overflow-y:hidden;

            color:white;

            display:flex;

            flex-direction:column;

            box-shadow:0 15px 35px rgba(0,0,0,.15);

            z-index:1000;
        }

        .sidebar ul{
            flex:1;

            overflow-y:auto;
        }

        .sidebar-logo{
            text-align:center;
            margin-bottom:45px;
        }

        .sidebar-logo i{
            font-size:46px;
        }

        .sidebar-logo h4{
            margin-top:12px;
            font-weight:700;
        }

        .sidebar ul{
            list-style:none;
            padding:0;
        }

        .sidebar li{
            margin-bottom:12px;
        }

        .sidebar a{
            color:white;
            display:flex;
            align-items:center;
            gap:12px;
            padding:15px 18px;
            border-radius:15px;
            transition:.3s;
            font-weight:500;
        }

        .sidebar a:hover{
            background:rgba(255,255,255,.18);
            transform:translateX(5px);
        }

        .active-menu{
            background:rgba(255,255,255,.18);
            border-left:4px solid #fff;
            font-weight:600;
        }

        .sidebar .badge{
            font-size:10px;
        }

        .sidebar hr{
            margin:25px 0;
            border-color:rgba(255,255,255,.2);
    }

        

        /*=====================
            CONTENT
        =====================*/

        .main-content{

            margin-left:330px;
            padding:30px;
            min-height:100vh;

        }

        /*=====================
            NAVBAR
        =====================*/

        .navbar-custom{

            background:var(--glass);

            backdrop-filter:blur(18px);

            border-radius:22px;

            padding:18px 28px;

            border:1px solid rgba(255,255,255,.45);

            box-shadow:0 10px 35px rgba(0,0,0,.08);

            margin-bottom:28px;
            
            position: relative;
            
            z-index: 1050;

        }

        /*=====================
            CARD
        =====================*/

        .card-modern{

            border:none;

            background:rgba(255,255,255,.82);

            backdrop-filter:blur(16px);

            border-radius:22px;

            box-shadow:0 15px 40px rgba(0,0,0,.08);

            transition:.35s;

        }

        .card-modern:hover{

            transform:translateY(-5px);

        }

        .stat-icon{

            width:65px;

            height:65px;

            border-radius:18px;

            display:flex;

            justify-content:center;

            align-items:center;

            color:white;

            font-size:28px;

        }

        .bg-blue{
            background:var(--primary);
        }

        .bg-green{
            background:var(--success);
        }

        .bg-orange{
            background:var(--warning);
        }

        .bg-red{
            background:var(--danger);
        }

        /*=====================
            BUTTON
        =====================*/

        .btn{

            border-radius:12px;

            font-weight:500;

            transition:.3s;

        }

        .btn:hover{

            transform:translateY(-2px);

        }

        /*=====================
            TABLE
        =====================*/

        .table{

            vertical-align:middle;

        }

        .table thead{

            background:#eff6ff;

        }

        .table th{

            color:#475569;

            font-weight:600;

        }

        /*=====================
            FOOTER
        =====================*/

        footer{

            margin-top:35px;

            text-align:center;

            color:#94a3b8;

        }

        /*=====================
            SCROLLBAR
        =====================*/

        ::-webkit-scrollbar{

            width:8px;

        }

        ::-webkit-scrollbar-thumb{

            background:#94a3b8;

            border-radius:50px;

        }

        /*=====================
            RESPONSIVE
        =====================*/

        @media(max-width:992px){

            .sidebar{

                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
                z-index: 1050;

            }

            .sidebar.show {
                transform: translateX(0);
                display: flex;
            }

            .main-content{

                margin-left:0;

                padding:18px;

            }

            .left-side{

                display:none;

            }

            .right-side{

                padding:35px;

            }

        }

    </style>

    @stack('styles')

</head>

<body>

@yield('content')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

<script>
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('show');
    }
</script>

</body>
</html>