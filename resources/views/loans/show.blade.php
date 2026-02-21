<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Peminjaman - LibraryID</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bookdetail.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user-sidebar.css') }}">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-dark: #3a56d4;
            --secondary-color: #7209b7;
            --success-color: #4cc9f0;
            --warning-color: #f8961e;
            --danger-color: #f72585;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --gray-color: #6c757d;
            --light-gray: #e9ecef;
            --border-radius: 12px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            color: var(--dark-color);
            line-height: 1.6;
            min-height: 100vh;
        }

        .main-content {
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .detail-container {
            max-width: 1100px;
            margin: 0 auto;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Back Button */
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 2rem;
            padding: 12px 20px;
            background: white;
            color: var(--primary-color);
            text-decoration: none;
            border-radius: var(--border-radius);
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.15);
            transition: var(--transition);
            border: 1px solid rgba(67, 97, 238, 0.1);
        }

        .back-btn:hover {
            transform: translateX(-5px);
            box-shadow: 0 6px 20px rgba(67, 97, 238, 0.25);
            background: var(--primary-color);
            color: white;
        }

        /* Header */
        .detail-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .detail-header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 0;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status-approved {
            background: linear-gradient(135deg, #fff8e1 0%, #ffe082 100%);
            color: #f57f17;
            border: 1px solid #ffe082;
        }

        .status-active {
            background: linear-gradient(135deg, #d1ecf1 0%, #a8e6cf 100%);
            color: #0c5460;
            border: 1px solid #a8e6cf;
        }

        .status-returned {
            background: linear-gradient(135deg, #d4edda 0%, #c7ecee 100%);
            color: #155724;
            border: 1px solid #c7ecee;
        }

        .status-overdue {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Section Cards */
        .section-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 2rem;
            overflow: hidden;
            transition: var(--transition);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .section-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 1.5rem 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-header h2 {
            font-size: 1.3rem;
            font-weight: 600;
            margin: 0;
            color: white;
        }

        .card-body {
            padding: 2rem;
        }

        /* Modal Button Styles */
        .modal-btn {
            padding: 0.9rem 1.8rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.7rem;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            text-decoration: none;
        }

        .modal-btn:active {
            transform: scale(0.98);
        }

        .btn-primary-modal {
            background: linear-gradient(135deg, #4361ee 0%, #3a56d4 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }

        .btn-primary-modal:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
        }

        .btn-primary-modal:active {
            transform: scale(0.98);
        }

        .btn-secondary-modal {
            background: white;
            color: #4361ee;
            border: 2px solid #4361ee;
        }

        .btn-secondary-modal:hover {
            background: #4361ee;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(67, 97, 238, 0.3);
        }

        .btn-secondary-modal:active {
            transform: scale(0.98);
        }

        /* User Info Section */
        .user-info-section {
            display: flex;
            align-items: center;
            gap: 2rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: calc(var(--border-radius) - 4px);
        }

        .user-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 42px;
            color: white;
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-avatar-text {
            font-size: 2rem;
            font-weight: bold;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            flex-grow: 1;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .info-label {
            font-size: 12px;
            color: var(--gray-color);
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .info-value {
            font-size: 16px;
            color: var(--dark-color);
            font-weight: 500;
        }

        .info-value.secondary {
            color: var(--gray-color);
            font-weight: 400;
        }

        /* Book Info Section */
        .book-info-section {
            display: flex;
            gap: 2rem;
            align-items: flex-start;
        }

        .book-cover {
            width: 150px;
            height: 200px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            flex-shrink: 0;
            transition: var(--transition);
        }

        .book-cover:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .book-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .book-details-info {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }

        .book-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 0;
            line-height: 1.3;
        }

        .book-author {
            font-size: 15px;
            color: var(--gray-color);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .book-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 1.5rem;
            margin-top: 0.5rem;
        }

        .meta-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            padding: 1rem;
            background: var(--light-color);
            border-radius: 10px;
            border-left: 4px solid var(--primary-color);
        }

        .meta-label {
            font-size: 11px;
            color: var(--gray-color);
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .meta-value {
            font-size: 15px;
            color: var(--dark-color);
            font-weight: 500;
        }

        /* Timeline Section */
        .timeline-section {
            position: relative;
            padding-left: 30px;
        }

        .timeline-section::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
        }

        .timeline-item {
            position: relative;
            margin-bottom: 2rem;
            padding-left: 2rem;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -8px;
            top: 0;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: var(--primary-color);
            border: 3px solid white;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }

        .timeline-date {
            font-size: 14px;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .timeline-label {
            font-size: 16px;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.25rem;
        }

        /* Pickup Code Section */
        .pickup-code-section {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            color: white;
            padding: 2.5rem;
            border-radius: var(--border-radius);
            text-align: center;
            margin: 2.5rem 0;
            box-shadow: 0 10px 30px rgba(46, 204, 113, 0.3);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 10px 30px rgba(46, 204, 113, 0.3); }
            50% { box-shadow: 0 10px 40px rgba(46, 204, 113, 0.5); }
            100% { box-shadow: 0 10px 30px rgba(46, 204, 113, 0.3); }
        }

        .pickup-code-label {
            font-size: 14px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 1px;
            opacity: 0.9;
            margin: 0 0 1rem 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .pickup-code {
            font-size: 3rem;
            font-weight: 800;
            font-family: 'Courier New', monospace;
            margin: 1.5rem 0;
            letter-spacing: 0.2em;
            background: rgba(255, 255, 255, 0.1);
            padding: 1.5rem;
            border-radius: 12px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
        }

        .copy-success {
            position: absolute;
            bottom: -25px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            color: #27ae60;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .copy-success.show {
            opacity: 1;
        }

        .pickup-code-info {
            font-size: 13px;
            opacity: 0.85;
            margin: 1rem 0 2rem 0;
            line-height: 1.6;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .pickup-code-button {
            padding: 14px 32px;
            background: white;
            color: #27ae60;
            border: none;
            border-radius: 50px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .pickup-code-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            background: #f8f9fa;
        }

        /* Return Book Section */
        .return-section {
            background: linear-gradient(135deg, #ff9a00 0%, #ff6a00 100%);
            color: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            margin: 2rem 0;
        }

        .return-header {
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .return-header h2 {
            font-size: 1.3rem;
            font-weight: 600;
            margin: 0;
            color: white;
        }

        .return-body {
            background: rgba(255, 255, 255, 0.1);
            padding: 2rem;
            backdrop-filter: blur(10px);
        }

        /* Alerts */
        .alert-box {
            padding: 1.5rem;
            border-radius: var(--border-radius);
            margin: 1.5rem 0;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            border-left: 5px solid;
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .alert-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-left-color: #ffc107;
            color: #856404;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c7ecee 100%);
            border-left-color: #28a745;
            color: #155724;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border-left-color: #dc3545;
            color: #721c24;
        }

        .alert-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            border-left-color: #17a2b8;
            color: #0c5460;
        }

        .alert-box i {
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .alert-content {
            flex-grow: 1;
        }

        .alert-content p {
            margin: 0.5rem 0 0 0;
            font-size: 14px;
            line-height: 1.5;
        }

        /* Duration Info */
        .duration-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--light-gray);
        }

        .duration-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .duration-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0.5rem 0;
        }

        .duration-label {
            font-size: 13px;
            color: var(--gray-color);
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Progress Bar */
        .progress-container {
            margin: 1.5rem 0;
        }

        .progress-bar {
            height: 10px;
            background: var(--light-gray);
            border-radius: 5px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #4cc9f0, #4361ee);
            border-radius: 5px;
            transition: width 1s ease;
        }

        .progress-labels {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: var(--gray-color);
        }

        /* Buttons */
        .btn {
            padding: 12px 28px;
            border: none;
            border-radius: 50px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: var(--transition);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(67, 97, 238, 0.3);
            border: 2px solid var(--primary-color);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(67, 97, 238, 0.4);
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-primary:active {
            transform: translateY(-1px);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, #d63384 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(247, 37, 133, 0.3);
        }

        .btn-danger:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(247, 37, 133, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(40, 167, 69, 0.4);
        }

        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(255, 193, 7, 0.3);
        }

        .btn-warning:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(255, 193, 7, 0.4);
        }

        .action-section {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        /* Fine Badge */
        .fine-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: linear-gradient(135deg, #ffe6e6 0%, #ffcccc 100%);
            color: #d63384;
            border-radius: 50px;
            font-weight: 600;
            margin-top: 1rem;
            border: 1px solid #ffcccc;
        }

        /* Loader */
        .loader {
            border: 3px solid #f3f3f3;
            border-top: 3px solid var(--primary-color);
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* QR Code Section */
        .qr-section {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: var(--border-radius);
            margin: 2rem 0;
            box-shadow: var(--box-shadow);
        }

        .qr-container {
            width: 200px;
            height: 200px;
            margin: 1rem auto;
            padding: 15px;
            background: white;
            border-radius: 8px;
            border: 1px solid var(--light-gray);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .book-info-section {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            
            .book-meta {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .detail-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .detail-header h1 {
                font-size: 1.8rem;
            }
            
            .book-meta {
                grid-template-columns: 1fr;
            }
            
            .user-info-section {
                flex-direction: column;
                text-align: center;
                gap: 1.5rem;
            }
            
            .pickup-code {
                font-size: 2rem;
                letter-spacing: 0.1em;
            }
            
            .duration-info {
                grid-template-columns: 1fr;
            }
            
            .card-body {
                padding: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 1rem;
            }
            
            .detail-header h1 {
                font-size: 1.5rem;
            }
            
            .pickup-code {
                font-size: 1.5rem;
                padding: 1rem;
            }
            
            .timeline-section {
                padding-left: 20px;
            }
            
            .timeline-item {
                padding-left: 1.5rem;
            }
            
            .action-section {
                flex-direction: column;
            }
            
            .action-section .btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Print Styles - IMPROVED FOR PDF */
        @media print {
            /* Reset untuk PDF */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            @page {
                size: A4 portrait;
                margin: 20mm 15mm 25mm 15mm;
                @top-center {
                    content: "LibraryID - Detail Peminjaman Buku";
                    font-size: 9pt;
                    color: #999;
                    font-weight: 600;
                }
                @bottom-center {
                    content: "Halaman " counter(page) " dari " counter(pages);
                    font-size: 8pt;
                    color: #999;
                }
                @bottom-right {
                    content: string(print-date);
                    font-size: 8pt;
                    color: #999;
                }
            }

            html, body {
                width: 210mm;
                height: 297mm;
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
                color: #000 !important;
                font-size: 10pt !important;
                line-height: 1.5 !important;
                overflow: visible;
            }

            body {
                font-family: 'Poppins', 'Segoe UI', Arial, sans-serif !important;
                padding: 20mm 15mm !important;
                width: auto !important;
                height: auto !important;
            }

            /* Hide unnecessary elements */
            .navbar,
            .user-sidebar,
            .sidebar-toggle,
            .sidebar-overlay,
            .back-btn,
            .notification-popup-modal,
            .action-section,
            .pickup-code-button,
            .return-body button,
            .copy-success,
            .qr-section,
            .return-section .btn,
            .btn,
            .notification-trigger,
            .profile-badge,
            .btn-logout,
            #extensionModal {
                display: none !important;
            }

            /* Layout untuk PDF */
            .main-content {
                min-height: auto !important;
                padding: 0 !important;
                margin: 0 !important;
                background: white !important;
            }

            .detail-container {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100%;
                box-shadow: none !important;
            }

            /* Header untuk PDF */
            .detail-header {
                margin-bottom: 10mm !important;
                page-break-after: avoid;
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 2px solid #4361ee;
                padding-bottom: 5mm;
                padding-top: 5mm;
            }

            .detail-header h1 {
                font-size: 18pt !important;
                margin: 0 !important;
                font-weight: 700;
                color: #000 !important;
                background: none !important;
                -webkit-background-clip: initial !important;
                background-clip: initial !important;
                -webkit-text-fill-color: initial !important;
            }

            .status-badge {
                padding: 3mm 6mm !important;
                font-size: 8pt !important;
                font-weight: 700;
                border-radius: 3px;
                white-space: nowrap;
                flex-shrink: 0;
                box-shadow: 0 1px 2px rgba(0,0,0,0.15);
                border: 1px solid currentColor !important;
                text-transform: uppercase;
                letter-spacing: 0.3px;
            }

            /* Cards untuk PDF */
            .section-card {
                border: 1px solid #ddd !important;
                box-shadow: none !important;
                margin-bottom: 8mm !important;
                page-break-inside: avoid;
                break-inside: avoid;
                background: white !important;
                border-radius: 0;
            }

            .section-card:hover {
                transform: none !important;
                box-shadow: none !important;
            }

            .card-header {
                background: #4361ee !important;
                color: white !important;
                padding: 4mm 5mm !important;
                border-bottom: none !important;
                display: flex;
                align-items: center;
                gap: 3mm;
                border-radius: 0;
            }

            .card-header h2 {
                font-size: 11pt !important;
                margin: 0 !important;
                font-weight: 700;
                color: white !important;
                text-transform: uppercase;
                letter-spacing: 0.3px;
            }

            .card-header i {
                font-size: 10pt !important;
            }

            .card-body {
                padding: 5mm !important;
                background: white !important;
            }

            /* User Info untuk PDF */
            .user-info-section {
                background: #f5f5f5 !important;
                padding: 4mm !important;
                border-radius: 0 !important;
                page-break-inside: avoid;
                display: flex;
                gap: 5mm;
                align-items: flex-start;
            }

            .user-avatar {
                width: 20mm !important;
                height: 20mm !important;
                min-width: 20mm;
                font-size: 12pt !important;
                border-radius: 50%;
                background: #4361ee !important;
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 1px 2px rgba(0,0,0,0.2) !important;
                border: 2px solid white;
                outline: 1px solid #ddd;
                flex-shrink: 0;
            }

            .user-avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .info-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 3mm !important;
                flex: 1;
            }

            .info-item {
                display: flex;
                flex-direction: column;
                gap: 1mm;
            }

            .info-label {
                font-size: 7pt !important;
                color: #666 !important;
                text-transform: uppercase;
                font-weight: 700;
                letter-spacing: 0.4px;
            }

            .info-value {
                font-size: 10pt !important;
                color: #000 !important;
                font-weight: 600;
            }

            .info-value.secondary {
                color: #666 !important;
                font-weight: 400;
                font-size: 9pt !important;
            }

            /* Book Info untuk PDF */
            .book-info-section {
                display: flex;
                gap: 5mm !important;
                align-items: flex-start;
                page-break-inside: avoid;
            }

            .book-cover {
                width: 30mm !important;
                height: 40mm !important;
                min-width: 30mm;
                border-radius: 0;
                overflow: hidden;
                box-shadow: 0 1px 2px rgba(0,0,0,0.2) !important;
                border: 1px solid #ccc;
                flex-shrink: 0;
            }

            .book-cover img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }

            .book-details-info {
                flex: 1;
                display: flex;
                flex-direction: column;
                gap: 2mm;
            }

            .book-title {
                font-size: 12pt !important;
                font-weight: 700;
                color: #000 !important;
                margin: 0;
                line-height: 1.3;
            }

            .book-author {
                font-size: 9pt !important;
                color: #666 !important;
                margin: 0;
            }

            .book-meta {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 3mm !important;
                margin-top: 2mm !important;
            }

            .meta-item {
                display: flex;
                flex-direction: column;
                gap: 1mm;
                padding: 3mm !important;
                background: #f5f5f5 !important;
                border-radius: 0;
                border-left: 3px solid #4361ee !important;
                border: 1px solid #ddd;
            }

            .meta-label {
                font-size: 7pt !important;
                color: #666 !important;
                text-transform: uppercase;
                font-weight: 700;
            }

            .meta-value {
                font-size: 10pt !important;
                color: #000 !important;
                font-weight: 600;
            }

            /* Timeline untuk PDF */
            .timeline-section {
                position: relative;
                padding-left: 5mm;
                page-break-inside: avoid;
            }

            .timeline-section::before {
                content: '';
                position: absolute;
                left: 0;
                top: 0;
                bottom: 0;
                width: 2px;
                background: #4361ee !important;
            }

            .timeline-item {
                position: relative;
                margin-bottom: 5mm !important;
                padding-left: 5mm;
                page-break-inside: avoid;
            }

            .timeline-item::before {
                content: '';
                position: absolute;
                left: -4mm;
                top: 1mm;
                width: 8mm !important;
                height: 8mm !important;
                border-radius: 50%;
                background: #4361ee !important;
                border: 2px solid white !important;
                outline: 2px solid #4361ee;
            }

            .timeline-date {
                font-size: 9pt !important;
                font-weight: 700;
                color: #4361ee !important;
                margin-bottom: 1mm;
                display: flex;
                align-items: center;
                gap: 2mm;
            }

            .timeline-label {
                font-size: 10pt !important;
                font-weight: 700;
                color: #000 !important;
                margin: 0;
            }

            .timeline-label + small {
                font-size: 9pt !important;
                color: #666 !important;
                display: block;
                margin-top: 1mm;
            }

            /* Progress Bar untuk PDF */
            .progress-container {
                margin: 4mm 0 !important;
            }

            .progress-bar {
                height: 5mm !important;
                background: #ddd !important;
                border-radius: 0;
                overflow: hidden;
                margin-bottom: 2mm;
                border: 1px solid #999;
            }

            .progress-fill {
                height: 100%;
                background: linear-gradient(90deg, #4cc9f0, #4361ee) !important;
                border-radius: 0;
            }

            .progress-labels {
                display: flex;
                justify-content: space-between;
                font-size: 9pt !important;
                color: #666 !important;
            }

            /* Duration Info untuk PDF */
            .duration-info {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 3mm !important;
                margin-top: 5mm !important;
                padding-top: 5mm !important;
                border-top: 2px solid #ddd;
                page-break-inside: avoid;
            }

            .duration-card {
                padding: 4mm !important;
                background: #f5f5f5 !important;
                border-radius: 0;
                text-align: center;
                border: 1px solid #ddd !important;
            }

            .duration-value {
                font-size: 18pt !important;
                font-weight: 900;
                color: #4361ee !important;
                margin: 2mm 0;
            }

            .duration-label {
                font-size: 8pt !important;
                color: #666 !important;
                text-transform: uppercase;
                font-weight: 700;
                margin: 0;
                letter-spacing: 0.3px;
            }

            .duration-card small {
                font-size: 9pt !important;
                color: #666 !important;
                display: block;
                margin-top: 2mm;
            }

            /* Fine Badge untuk PDF */
            .fine-badge {
                display: inline-flex;
                align-items: center;
                gap: 2mm;
                padding: 3mm 5mm !important;
                background: #ffcccc !important;
                color: #d63384 !important;
                border-radius: 0;
                font-weight: 700;
                margin-top: 5mm;
                border: 2px solid #ff9999 !important;
                font-size: 10pt !important;
                page-break-inside: avoid;
                text-transform: uppercase;
                letter-spacing: 0.3px;
            }

            /* Alerts untuk PDF */
            .alert-box {
                padding: 4mm !important;
                border-radius: 0;
                margin: 8mm 0 !important;
                display: flex;
                align-items: flex-start;
                gap: 3mm;
                border-left: 5px solid !important;
                page-break-inside: avoid;
                background-color: #f5f5f5 !important;
                border: 1px solid #ddd;
            }

            .alert-box i {
                font-size: 12pt !important;
                flex-shrink: 0;
            }

            .alert-content {
                flex: 1;
            }

            .alert-content strong {
                display: block;
                margin-bottom: 2mm;
                font-size: 10pt !important;
                color: #000 !important;
                text-transform: uppercase;
                letter-spacing: 0.3px;
            }

            .alert-content p {
                margin: 0;
                font-size: 10pt !important;
                line-height: 1.5;
                color: #333 !important;
            }

            .alert-warning {
                background: #fff3cd !important;
                border-left-color: #ffc107 !important;
                color: #856404 !important;
            }

            .alert-success {
                background: #d4edda !important;
                border-left-color: #28a745 !important;
                color: #155724 !important;
            }

            .alert-danger {
                background: #f8d7da !important;
                border-left-color: #dc3545 !important;
                color: #721c24 !important;
            }

            /* Pickup Code untuk PDF */
            .pickup-code-section {
                background: #2ecc71 !important;
                color: white !important;
                padding: 5mm !important;
                border-radius: 0;
                text-align: center;
                margin: 8mm 0 !important;
                box-shadow: 0 1px 3px rgba(0,0,0,0.2) !important;
                page-break-inside: avoid;
                border: 2px solid #27ae60;
            }

            .pickup-code-label {
                font-size: 10pt !important;
                text-transform: uppercase;
                font-weight: 700;
                letter-spacing: 0.5px;
                opacity: 0.95;
                margin: 0 0 3mm 0;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 2mm;
            }

            .pickup-code {
                font-size: 24pt !important;
                font-weight: 900;
                font-family: 'Courier New', monospace !important;
                margin: 4mm 0;
                letter-spacing: 0.2em;
                background: rgba(255, 255, 255, 0.2) !important;
                padding: 4mm 3mm !important;
                border-radius: 0;
                border: 2px dashed rgba(255, 255, 255, 0.5) !important;
                word-break: break-all;
            }

            .pickup-code-info {
                font-size: 9pt !important;
                opacity: 0.85;
                margin: 3mm 0;
                line-height: 1.4;
            }

            /* Return Section untuk PDF */
            .return-section {
                background: #ff9a00 !important;
                color: white !important;
                border-radius: 0;
                overflow: hidden;
                margin: 8mm 0 !important;
                page-break-inside: avoid;
                border: 2px solid #ff7700;
            }

            .return-header {
                padding: 4mm 5mm !important;
                background: #ff7700 !important;
                display: flex;
                align-items: center;
                gap: 3mm;
            }

            .return-header h2 {
                font-size: 11pt !important;
                margin: 0;
                font-weight: 700;
                color: white !important;
                text-transform: uppercase;
                letter-spacing: 0.3px;
            }

            .return-body {
                background: rgba(255, 255, 255, 0.1) !important;
                padding: 5mm !important;
            }

            .return-body p {
                margin: 0 0 3mm 0;
                font-size: 10pt !important;
                line-height: 1.4;
            }

            .return-body ol {
                font-size: 10pt !important;
                margin: 0;
                padding-left: 5mm;
                line-height: 1.6;
            }

            .return-body li {
                margin-bottom: 2mm;
            }

            /* QR Code untuk PDF */
            .qr-section {
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1) !important;
                margin: 8mm 0 !important;
                page-break-inside: avoid;
                border: 1px solid #ddd;
                border-radius: 0;
                padding: 5mm;
                text-align: center;
            }

            .qr-container {
                width: 35mm !important;
                height: 35mm !important;
                margin: 3mm auto !important;
                border: 2px solid #333 !important;
                padding: 2mm;
                background: white;
            }

            /* Footer untuk PDF */
            .pdf-footer {
                margin-top: 12mm;
                padding-top: 5mm;
                border-top: 2px solid #4361ee;
                font-size: 10pt;
                color: #666;
                text-align: center;
                page-break-before: avoid;
            }

            .pdf-footer p {
                margin: 2mm 0;
                font-size: 9pt !important;
            }

            .pdf-footer .signature {
                margin-top: 10mm;
                display: flex;
                justify-content: space-between;
            }

            .pdf-footer .signature div {
                width: 45%;
                text-align: center;
            }

            .pdf-footer .signature-line {
                margin-top: 20mm;
                border-top: 1px solid #000;
                width: 80%;
                margin-left: auto;
                margin-right: auto;
                font-size: 9pt !important;
                padding-top: 2mm;
            }

            /* Garis pemisah untuk setiap section */
            .section-divider {
                height: 2px;
                background: #ddd;
                margin: 8mm 0;
                page-break-before: avoid;
            }

            /* Page break management */
            h1, h2, h3 {
                page-break-after: avoid;
            }

            table, figure {
                page-break-inside: avoid;
            }

            /* Ensure no colors bleed */
            * {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                color-adjust: exact;
            }
        }
    </style>
</head>
<body class="sidebar-page">
    @include('partials.user-sidebar')
    <!-- ===== Navbar ===== -->


    <!-- Main Content -->
    <div class="main-content content-with-sidebar">
        <div class="detail-container">
            <a href="{{ route('loans.history') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
            </a>

            <!-- Header -->
            <div class="detail-header">
                <h1>Detail Peminjaman Buku</h1>
                @php
                    $isOverdue = $loan->status === 'active' && $loan->due_date && now()->gt($loan->due_date);
                    $statusClass = $isOverdue ? 'overdue' : $loan->status;
                @endphp
                <span class="status-badge status-{{ $statusClass }}">
                    @if($isOverdue)
                        <i class="fas fa-exclamation-triangle"></i> Terlambat
                    @elseif($loan->status === 'pending')
                        <i class="fas fa-clock"></i> Menunggu Persetujuan
                    @elseif($loan->status === 'approved')
                        <i class="fas fa-key"></i> Siap Diambil
                    @elseif($loan->status === 'active')
                        <i class="fas fa-book-reader"></i> Sedang Dipinjam
                    @elseif($loan->status === 'returned')
                        <i class="fas fa-check-circle"></i> Sudah Dikembalikan
                    @else
                        {{ ucfirst($loan->status) }}
                    @endif
                </span>
            </div>

            <!-- User Information -->
            <div class="section-card">
                <div class="card-header">
                    <i class="fas fa-user-circle"></i>
                    <h2>Data Peminjam</h2>
                </div>
                <div class="card-body">
                    <div class="user-info-section">
                        <div class="user-avatar">
                            @if($loan->user->profile_picture)
                                <img src="{{ asset('storage/' . $loan->user->profile_picture) }}" alt="{{ $loan->user->name }}">
                            @else
                                <span class="user-avatar-text">{{ strtoupper(substr($loan->user->name, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-user"></i> Nama Lengkap</span>
                                <span class="info-value">{{ $loan->user->name }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-envelope"></i> Email</span>
                                <span class="info-value secondary">{{ $loan->user->email }}</span>
                            </div>
                            @if($loan->user->phone)
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-phone"></i> Nomor Telepon</span>
                                <span class="info-value secondary">{{ $loan->user->phone }}</span>
                            </div>
                            @endif
                            @if($loan->user->address)
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-map-marker-alt"></i> Alamat</span>
                                <span class="info-value secondary">{{ $loan->user->address }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Book Information -->
            <div class="section-card">
                <div class="card-header">
                    <i class="fas fa-book"></i>
                    <h2>Detail Buku</h2>
                </div>
                <div class="card-body">
                    <div class="book-info-section">
                        <div class="book-cover">
                            <img src="{{ route('books.cover', $loan->book->id) }}" alt="{{ $loan->book->title }}">
                        </div>
                        <div class="book-details-info">
                            <h3 class="book-title">{{ $loan->book->title }}</h3>
                            <p class="book-author"><i class="fas fa-user-edit"></i> Penulis: {{ $loan->book->author }}</p>
                            <div class="book-meta">
                                <div class="meta-item">
                                    <span class="meta-label">Penerbit</span>
                                    <span class="meta-value">{{ $loan->book->publisher ?? 'Tidak Diketahui' }}</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Tahun Terbit</span>
                                    <span class="meta-value">{{ $loan->book->publication_year ?? $loan->book->year ?? 'Tidak Diketahui' }}</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Halaman</span>
                                    <span class="meta-value">{{ $loan->book->pages ?? 'Tidak Diketahui' }}</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Kategori</span>
                                    <span class="meta-value">{{ $loan->book->category ?? 'Tidak Diketahui' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loan Timeline -->
            <div class="section-card">
                <div class="card-header">
                    <i class="fas fa-calendar-alt"></i>
                    <h2>Jadwal Peminjaman</h2>
                </div>
                <div class="card-body">
                    <div class="timeline-section">
                        <div class="timeline-item">
                            <div class="timeline-date">
                                <i class="fas fa-arrow-up"></i>
                                {{ $loan->borrow_date ? \Carbon\Carbon::parse($loan->borrow_date)->format('d M Y') : '-' }}
                            </div>
                            <div>
                                <div class="timeline-label">Tanggal Peminjaman</div>
                                <small style="color: #999;">Tanggal mulai peminjaman buku</small>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-date">
                                <i class="fas fa-clock"></i>
                                {{ $loan->due_date ? \Carbon\Carbon::parse($loan->due_date)->format('d M Y') : '-' }}
                            </div>
                            <div>
                                <div class="timeline-label">Jatuh Tempo</div>
                                <small style="color: #999;">Tanggal deadline pengembalian</small>
                            </div>
                        </div>

                        @if($loan->return_date)
                        <div class="timeline-item">
                            <div class="timeline-date">
                                <i class="fas fa-arrow-down"></i>
                                {{ \Carbon\Carbon::parse($loan->return_date)->format('d M Y') }}
                            </div>
                            <div>
                                <div class="timeline-label">Tanggal Pengembalian</div>
                                <small style="color: #999;">Buku telah dikembalikan</small>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Duration Info -->
                    @if($loan->borrow_date && $loan->due_date)
                    @php
                        $borrowDate = \Carbon\Carbon::parse($loan->borrow_date);
                        $dueDate = \Carbon\Carbon::parse($loan->due_date);
                        $loanDuration = $borrowDate->diffInDays($dueDate);
                        
                        $currentDate = $loan->return_date ? \Carbon\Carbon::parse($loan->return_date) : now();
                        $daysPassed = $borrowDate->diffInDays($currentDate);
                        $progressPercentage = $loanDuration > 0 ? min(100, ($daysPassed / $loanDuration) * 100) : 100;
                    @endphp
                    
                    <div class="progress-container">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $progressPercentage }}%"></div>
                        </div>
                        <div class="progress-labels">
                            <span>Mulai: {{ $borrowDate->format('d M Y') }}</span>
                            <span>Jatuh Tempo: {{ $dueDate->format('d M Y') }}</span>
                        </div>
                    </div>

                    <div class="duration-info">
                        <div class="duration-card">
                            <div class="duration-label">Durasi Peminjaman</div>
                            <div class="duration-value">{{ $loanDuration }}</div>
                            <small>hari</small>
                        </div>
                        
                        <div class="duration-card">
                            <div class="duration-label">Sudah Berlalu</div>
                            <div class="duration-value">{{ $daysPassed }}</div>
                            <small>hari</small>
                        </div>
                        
                        @if($loan->return_date)
                        <div class="duration-card">
                            <div class="duration-label">Durasi Aktual</div>
                            <div class="duration-value">
                                {{ $borrowDate->diffInDays(\Carbon\Carbon::parse($loan->return_date)) }}
                            </div>
                            <small>hari</small>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Fine Section -->
                    @if($loan->fine > 0)
                    <div class="fine-badge">
                        <i class="fas fa-exclamation-triangle"></i>
                        Denda: Rp {{ number_format($loan->fine, 0, ',', '.') }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Status Alerts -->
            @if($loan->status === 'pending')
            <div class="alert-box alert-warning">
                <i class="fas fa-hourglass-half"></i>
                <div class="alert-content">
                    <strong>Status: Menunggu Persetujuan</strong>
                    <p>Permintaan peminjaman Anda sedang ditinjau. Anda akan mendapat notifikasi saat ada keputusan dari admin. Estimasi waktu: 1-2 hari kerja.</p>
                </div>
            </div>
            @elseif($loan->status === 'approved')
            <div class="alert-box alert-success">
                <i class="fas fa-check-circle"></i>
                <div class="alert-content">
                    <strong>Status: Siap Diambil</strong>
                    <p>Permintaan Anda telah disetujui! Silakan datang ke perpustakaan dengan kode yang sudah diberikan di bawah.</p>
                </div>
            </div>
            @elseif($loan->status === 'active')
                @if($isOverdue)
                <div class="alert-box alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div class="alert-content">
                        <strong>Status: Terlambat!</strong>
                        <p>Buku belum dikembalikan melewati tanggal jatuh tempo. Silakan kembalikan segera untuk menghindari denda yang lebih besar.</p>
                    </div>
                </div>
                @else
                <div class="alert-box alert-success">
                    <i class="fas fa-book-reader"></i>
                    <div class="alert-content">
                        <strong>Status: Sedang Dipinjam</strong>
                        <p>Buku sedang Anda pinjam. Mohon kembalikan sebelum tanggal jatuh tempo: <strong>{{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}</strong></p>
                    </div>
                </div>
                @endif
            @elseif($loan->status === 'returned')
            <div class="alert-box alert-success">
                <i class="fas fa-check-double"></i>
                <div class="alert-content">
                    <strong>Status: Sudah Dikembalikan</strong>
                    <p>Terima kasih telah mengembalikan buku! Anda bisa membaca ulasan atau meminjam buku lainnya.</p>
                </div>
            </div>
            @endif

            <!-- Pickup/Return Code Section -->
            @if(in_array($loan->status, ['approved', 'active']))
            <div class="pickup-code-section" style="@if($loan->status === 'active') background: linear-gradient(135deg, #ff9a00 0%, #ff6a00 100%); @endif">
                <p class="pickup-code-label">
                    <i class="fas fa-barcode"></i>
                    {{ $loan->status === 'approved' ? 'Kode Pengambilan Buku' : 'Kode Pengembalian Buku' }}
                </p>
                <div class="pickup-code" id="loanCode">
                    {{ $loan->barcode }}
                    <span class="copy-success" id="copySuccess">Kode disalin!</span>
                </div>
                <p class="pickup-code-info">
                    @if($loan->status === 'approved')
                    Salin kode di atas dan tunjukkan ke petugas saat Anda mengambil buku di perpustakaan.
                    @else
                    Salin kode di atas dan tunjukkan ke petugas saat Anda mengembalikan buku. Petugas akan memverifikasi kode ini.
                    @endif
                </p>
                <div style="display: flex; gap: 1rem; flex-wrap: wrap; justify-content: center; margin-top: 1rem;">
                    <button class="pickup-code-button" onclick="copyCodeToClipboard()">
                        <i class="fas fa-copy"></i> Salin Kode
                    </button>
                </div>
            </div>
            @endif

            <!-- Return Book Instructions for Active Loans -->
            @if($loan->status === 'active')
            <div class="return-section">
                <div class="return-header">
                    <i class="fas fa-arrow-circle-down"></i>
                    <h2>Kembalikan Buku ke Perpustakaan</h2>
                </div>
                <div class="return-body">
                    <p style="margin: 0 0 1.5rem 0; opacity: 0.9; line-height: 1.6;">
                        Silakan mengikuti langkah-langkah di bawah untuk mengembalikan buku dengan lancar.
                    </p>
                    
                    <div style="background: rgba(255, 255, 255, 0.15); padding: 1.5rem; border-radius: 10px; margin-bottom: 1.5rem; backdrop-filter: blur(5px);">
                        <p style="margin: 0; font-size: 13px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; opacity: 0.9;">
                            <i class="fas fa-tasks"></i> Langkah Pengembalian
                        </p>
                        <ol style="margin: 0.75rem 0 0 0; font-size: 14px; line-height: 1.8; padding-left: 1.5rem;">
                            <li>Datang ke perpustakaan dengan membawa buku</li>
                            <li>Tunjukkan <strong>kode pengembalian di atas</strong> kepada petugas</li>
                            <li>Petugas akan memverifikasi kode dan kondisi buku</li>
                            <li>Jika tepat waktu, tidak ada denda</li>
                            <li>Klik tombol di bawah untuk konfirmasi pengembalian</li>
                        </ol>
                    </div>
                    
                    <form method="POST" action="{{ route('loans.return', $loan) }}" id="returnForm" onsubmit="return confirmReturn()">
                        @csrf
                        <button type="submit" class="btn btn-success" style="width: 100%; padding: 16px; font-size: 16px;">
                            <i class="fas fa-check"></i> Konfirmasi Pengembalian Buku
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Additional Information for Approved Loans -->
            @if($loan->status === 'approved')
            <div class="section-card">
                <div class="card-header">
                    <i class="fas fa-info-circle"></i>
                    <h2>Instruksi Pengambilan Buku</h2>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                        <div style="padding: 1.5rem; background: #f8f9fa; border-radius: 8px;">
                            <h4 style="margin: 0 0 1rem 0; color: var(--primary-color);"><i class="fas fa-map-marker-alt"></i> Lokasi</h4>
                            <p style="margin: 0; font-size: 14px;">Perpustakaan Utama, Lantai 1, Gedung A</p>
                        </div>
                        <div style="padding: 1.5rem; background: #f8f9fa; border-radius: 8px;">
                            <h4 style="margin: 0 0 1rem 0; color: var(--primary-color);"><i class="fas fa-clock"></i> Jam Operasional</h4>
                            <p style="margin: 0; font-size: 14px;">Senin-Jumat: 08:00 - 17:00<br>Sabtu: 08:00 - 15:00</p>
                        </div>
                        <div style="padding: 1.5rem; background: #f8f9fa; border-radius: 8px;">
                            <h4 style="margin: 0 0 1rem 0; color: var(--primary-color);"><i class="fas fa-user-check"></i> Persyaratan</h4>
                            <p style="margin: 0; font-size: 14px;">Membawa kartu identitas atau kartu perpustakaan</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- PDF Footer (Hanya tampil saat print) -->
            <div class="pdf-footer" style="display: none;">
                <div class="section-divider"></div>
                <p style="font-size: 9pt; color: #666; margin-bottom: 5mm;">
                    <strong>Catatan:</strong> Dokumen ini dicetak secara otomatis dari sistem LibraryID. Validitas dokumen dapat diverifikasi dengan kode peminjaman.
                </p>
                <div class="signature">
                    <div>
                        <div class="signature-line"></div>
                        <p style="margin-top: 2mm;">Tanda Tangan Peminjam</p>
                        <p style="font-size: 8pt; color: #999;">{{ $loan->user->name }}</p>
                    </div>
                    <div>
                        <div class="signature-line"></div>
                        <p style="margin-top: 2mm;">Tanda Tangan Petugas</p>
                        <p style="font-size: 8pt; color: #999;">Perpustakaan LibraryID</p>
                    </div>
                </div>
                <p style="margin-top: 5mm; font-size: 8pt; color: #999;">
                    Dicetak pada: {{ now()->format('d M Y H:i:s') }} | ID Peminjaman: {{ $loan->id }}
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="action-section">
                @if($loan->status === 'pending')
                    <form method="POST" action="{{ route('loans.cancel', $loan) }}" id="cancelForm">
                        @csrf
                        <button type="button" class="btn btn-danger" onclick="confirmCancel()">
                            <i class="fas fa-times"></i> Batalkan Permintaan
                        </button>
                    </form>
                    
                    <!-- Request Extension for Active Loans -->
                    @elseif($loan->status === 'active' && !$isOverdue)
                    <button type="button" class="btn btn-warning" onclick="requestExtension()">
                        <i class="fas fa-calendar-plus"></i> Ajukan Perpanjangan
                    </button>
                    
                    <!-- Pay Fine Button for Overdue Loans -->
                    @elseif($isOverdue)
                    <button type="button" class="btn btn-danger" onclick="payFine()">
                        <i class="fas fa-money-bill-wave"></i> Bayar Denda
                    </button>
                    
                    <!-- Rate Book for Returned Loans -->
                    @elseif($loan->status === 'returned' && !$loan->hasReview())
                    <button type="button" class="btn btn-primary" onclick="rateBook()">
                        <i class="fas fa-star"></i> Beri Rating Buku
                    </button>
                @endif
                
                <!-- Always Show Print Button -->
                <button type="button" class="btn btn-primary" onclick="triggerPrint()" title="Cetak atau simpan sebagai PDF (Ctrl+P)">
                    <i class="fas fa-print"></i> Cetak / Simpan PDF
                </button>
                
                <!-- Admin Actions (if user is admin) -->
                @if(auth()->user()->isAdmin())
                    <div style="width: 100%; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--light-gray);">
                        <h4 style="margin-bottom: 0.5rem; color: var(--gray-color); font-size: 14px;">Admin Actions</h4>
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            @if($loan->status === 'pending')
                                <form method="POST" action="{{ route('admin.loans.approve', $loan) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success" style="padding: 8px 16px; font-size: 13px;">
                                        <i class="fas fa-check"></i> Setujui
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.loans.reject', $loan) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger" style="padding: 8px 16px; font-size: 13px;">
                                        <i class="fas fa-times"></i> Tolak
                                    </button>
                                </form>
                            @endif
                            
                            @if($loan->status === 'active')
                                <button type="button" class="btn btn-warning" style="padding: 8px 16px; font-size: 13px;" onclick="markAsLost()">
                                    <i class="fas fa-exclamation-triangle"></i> Tandai Hilang
                                </button>
                            @endif
                            
                            <a href="{{ route('admin.loans.edit', $loan) }}" class="btn btn-primary" style="padding: 8px 16px; font-size: 13px;">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Modal Detail Peminjaman -->
    <div id="loanDetailModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1001; align-items: center; justify-content: center; overflow-y: auto; padding: 2rem 0; backdrop-filter: blur(2px);">
        <div style="background: white; margin: auto; border-radius: 16px; width: 95%; max-width: 750px; box-shadow: 0 25px 50px rgba(0,0,0,0.3); animation: slideUp 0.3s ease;">
            <!-- Modal Header -->
            <div style="background: linear-gradient(135deg, #4361ee 0%, #3a56d4 100%); padding: 2.5rem 2rem; border-radius: 16px 16px 0 0; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="margin: 0 0 0.25rem 0; color: white; font-size: 1.8rem; font-weight: 700;">📋 Detail Peminjaman</h2>
                    <p style="margin: 0; color: rgba(255,255,255,0.9); font-size: 0.9rem;">Konfirmasi dari sistem LibraryID</p>
                </div>
                <button onclick="closeLoanDetailModal()" style="background: rgba(255,255,255,0.2); border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; color: white; font-size: 1.5rem; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">✕</button>
            </div>
            
            <!-- Modal Content (Printable) -->
            <div id="printableContent" style="padding: 2.5rem;">
                <!-- Header Section -->
                <div style="text-align: center; margin-bottom: 2.5rem; padding-bottom: 2rem; border-bottom: 3px solid var(--primary-color);">
                    <div style="background: linear-gradient(135deg, #4361ee10, #3a56d410); padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem;">
                        <h3 style="margin: 0 0 0.5rem 0; color: var(--primary-color); font-size: 1.4rem; font-weight: 700;">✓ PEMINJAMAN BERHASIL</h3>
                        <p style="margin: 0; color: #666; font-size: 0.95rem;">Dokumen ini adalah bukti resmi peminjaman buku Anda</p>
                    </div>
                    <p style="margin: 0; color: #999; font-size: 0.85rem;">Tanggal Pembuatan: {{ now()->format('d F Y, H:i') }}</p>
                </div>
                
                <!-- Detail Peminjaman -->
                <div style="margin-bottom: 2.5rem; background: #f8f9fe; padding: 1.5rem; border-radius: 12px; border-left: 4px solid var(--primary-color);">
                    <h4 style="color: var(--primary-color); margin: 0 0 1.25rem 0; font-size: 1.05rem; font-weight: 600;">📋 DETAIL PEMINJAMAN</h4>
                    <div style="display: grid; gap: 1rem;">
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                            <span style="font-weight: 600; color: #555;">Kode Barcode:</span>
                            <span style="color: #333; font-family: 'Courier New', monospace; font-weight: 500; font-size: 0.95rem; background: white; padding: 0.5rem 0.75rem; border-radius: 6px; border: 1px solid #ddd;">{{ $loan->barcode }}</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                            <span style="font-weight: 600; color: #555;">Status:</span>
                            <span style="display: inline-block; width: fit-content; padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.9rem; font-weight: 600;
                                @if($loan->status === 'pending')
                                    background: #fff3cd; color: #856404;
                                @elseif($loan->status === 'approved')
                                    background: #cfe2ff; color: #084298;
                                @elseif($loan->status === 'active')
                                    background: #d1ecf1; color: #0c5460;
                                @elseif($loan->status === 'returned')
                                    background: #d4edda; color: #155724;
                                @endif
                            ">{{ ucfirst($loan->status) }}</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                            <span style="font-weight: 600; color: #555;">Dari Tanggal:</span>
                            <span style="color: #333;">{{ \Carbon\Carbon::parse($loan->borrow_date)->format('d F Y') }}</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                            <span style="font-weight: 600; color: #555;">Sampai Tanggal:</span>
                            <span style="color: #333;">{{ \Carbon\Carbon::parse($loan->due_date)->format('d F Y') }}</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem; padding-top: 0.5rem; border-top: 2px solid rgba(67,97,238,0.2);">
                            <span style="font-weight: 600; color: var(--primary-color);">⏱ Durasi:</span>
                            <span style="color: var(--primary-color); font-weight: 600; font-size: 1.05rem;">
                                @php
                                    $days = \Carbon\Carbon::parse($loan->borrow_date)->diffInDays(\Carbon\Carbon::parse($loan->due_date));
                                @endphp
                                {{ $days }} hari
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Detail User -->
                <div style="margin-bottom: 2.5rem; background: #f0f8ff; padding: 1.5rem; border-radius: 12px; border-left: 4px solid #4cc9f0;">
                    <h4 style="color: #0c5460; margin: 0 0 1.25rem 0; font-size: 1.05rem; font-weight: 600;">👤 DATA PEMINJAM</h4>
                    <div style="display: grid; gap: 1rem;">
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                            <span style="font-weight: 600; color: #0c5460;">Nama Lengkap:</span>
                            <span style="color: #333;">{{ $loan->user->name }}</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                            <span style="font-weight: 600; color: #0c5460;">Email:</span>
                            <span style="color: #333; word-break: break-all;">{{ $loan->user->email }}</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                            <span style="font-weight: 600; color: #0c5460;">No. Identitas:</span>
                            <span style="color: #333;">{{ $loan->user->identity_number ?? '—' }}</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                            <span style="font-weight: 600; color: #0c5460;">Telepon:</span>
                            <span style="color: #333;">{{ $loan->user->phone ?? '—' }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Detail Buku -->
                <div style="margin-bottom: 2.5rem; background: #fffaf0; padding: 1.5rem; border-radius: 12px; border-left: 4px solid #f8961e;">
                    <h4 style="color: #856404; margin: 0 0 1.25rem 0; font-size: 1.05rem; font-weight: 600;">📚 DATA BUKU</h4>
                    <div style="display: grid; gap: 1rem;">
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                            <span style="font-weight: 600; color: #856404;">Judul:</span>
                            <span style="color: #333; font-weight: 500;">{{ $loan->book->title }}</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                            <span style="font-weight: 600; color: #856404;">Pengarang:</span>
                            <span style="color: #333;">{{ $loan->book->author ?? '—' }}</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                            <span style="font-weight: 600; color: #856404;">Penerbit:</span>
                            <span style="color: #333;">{{ $loan->book->publisher ?? '—' }}</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                            <span style="font-weight: 600; color: #856404;">ISBN:</span>
                            <span style="color: #333; font-family: 'Courier New', monospace;">{{ $loan->book->isbn ?? '—' }}</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                            <span style="font-weight: 600; color: #856404;">Tahun:</span>
                            <span style="color: #333;">{{ $loan->book->year ?? '—' }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Footer Note -->
                <div style="background: linear-gradient(135deg, rgba(67,97,238,0.05), rgba(114,9,183,0.05)); padding: 2rem; border-radius: 12px; margin-top: 2rem; text-align: center; color: #666; font-size: 0.9rem; line-height: 1.8; border-top: 2px solid var(--primary-color);">
                    <p style="margin: 0 0 0.75rem 0; font-weight: 500; color: #333;">✓ Dokumen telah dibuat dan terverifikasi</p>
                    <p style="margin: 0 0 0.75rem 0;">Simpan atau cetak dokumen ini sebagai bukti resmi peminjaman buku.</p>
                    <p style="margin: 0; color: #999; font-size: 0.85rem;">Jika ada pertanyaan, hubungi admin perpustakaan.</p>
                </div>
            </div>
            
            <!-- Modal Footer (Not Printable) -->
            <div style="display: flex; gap: 1rem; padding: 2rem; border-top: 2px solid #f0f0f0; justify-content: flex-end; background: linear-gradient(to right, #f8f9fa, #ffffff);">
                <button type="button" class="modal-btn btn-primary-modal" onclick="printLoanDetail(); return false;">
                    <i class="fas fa-download"></i> Download PDF
                </button>
                <button type="button" class="modal-btn btn-secondary-modal" onclick="closeLoanDetailModal(); return false;">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
    
    <style>
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    
    <!-- Notification Popup -->
    <div id="notificationPopup" class="notification-popup-modal" style="display: none;">
        <div class="notification-popup-content">
            <div class="notification-header">
                <h3>Notifikasi Peminjaman</h3>
                <span class="close-popup" onclick="closeNotificationPopup()">&times;</span>
            </div>
            <div class="notification-list">
                <div class="notification-empty" id="emptyState">
                    <p>Tidak ada notifikasi saat ini</p>
                </div>
                <div id="notificationItems"></div>
            </div>
        </div>
    </div>
    
    <!-- Modal for Extensions -->
    <div id="extensionModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div style="background: white; padding: 2rem; border-radius: var(--border-radius); max-width: 500px; width: 90%;">
            <h3 style="margin-bottom: 1rem;">Ajukan Perpanjangan Peminjaman</h3>
            <form id="extensionForm" method="POST" action="{{ route('loans.extend', $loan) }}">
                @csrf
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Tanggal Pengembalian Baru</label>
                    <input type="date" name="new_due_date" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--light-gray); border-radius: 8px;">
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Alasan Perpanjangan</label>
                    <textarea name="reason" rows="3" style="width: 100%; padding: 0.75rem; border: 1px solid var(--light-gray); border-radius: 8px;"></textarea>
                </div>
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">Ajukan</button>
                    <button type="button" class="btn btn-danger" onclick="closeExtensionModal()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Define notification routes
        const notificationRoutes = {
            get: '{{ route("notifications.get") }}',
            markRead: '{{ route("notifications.mark-read") }}',
            csrfToken: '{{ csrf_token() }}',
            loansHistory: '{{ route("loans.history") }}'
        };

        // Define user's loan data
        const userLoansData = {
            overdueCount: {{ isset($overdueLoans) ? $overdueLoans->count() : 0 }},
            pendingCount: {{ isset($pendingLoans) ? $pendingLoans->count() : 0 }},
            activeCount: {{ isset($activeLoans) ? $activeLoans->count() : 0 }}
        };

        // Copy code to clipboard
        function copyCodeToClipboard() {
            const code = document.getElementById('loanCode').textContent.trim();
            navigator.clipboard.writeText(code).then(() => {
                const copySuccess = document.getElementById('copySuccess');
                copySuccess.classList.add('show');
                setTimeout(() => {
                    copySuccess.classList.remove('show');
                }, 2000);
            });
        }

        // Confirm return
        function confirmReturn() {
            @if($isOverdue)
            const fine = {{ $loan->fine > 0 ? $loan->fine : 'calculateFine()' }};
            return confirm(`Buku terlambat dikembalikan. Denda yang harus dibayar: Rp ${formatNumber(fine)}. Lanjutkan pengembalian?`);
            @else
            return confirm('Konfirmasi pengembalian buku ini?');
            @endif
        }

        // Confirm cancel
        function confirmCancel() {
            if (confirm('Batalkan permintaan peminjaman ini?')) {
                document.getElementById('cancelForm').submit();
            }
        }

        // Format number with commas
        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Calculate fine for overdue loans
        function calculateFine() {
            @if($loan->due_date && now()->gt($loan->due_date))
                const daysOverdue = Math.floor((new Date() - new Date('{{ $loan->due_date }}')) / (1000 * 60 * 60 * 24));
                return daysOverdue * 3000; // Rp 3,000 per day
            @endif
            return 0;
        }

        // Request extension modal
        function requestExtension() {
            const modal = document.getElementById('extensionModal');
            modal.style.display = 'flex';
            
            // Set minimum date for extension (next day)
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const minDate = tomorrow.toISOString().split('T')[0];
            
            // Set maximum date (30 days from now)
            const maxDate = new Date();
            maxDate.setDate(maxDate.getDate() + 30);
            const maxDateStr = maxDate.toISOString().split('T')[0];
            
            const dateInput = document.querySelector('input[name="new_due_date"]');
            dateInput.min = minDate;
            dateInput.max = maxDateStr;
            dateInput.value = minDate;
        }

        function closeExtensionModal() {
            document.getElementById('extensionModal').style.display = 'none';
        }

        // Loan Detail Modal Functions
        function showLoanDetailModal() {
            document.getElementById('loanDetailModal').style.display = 'flex';
        }

        function closeLoanDetailModal() {
            document.getElementById('loanDetailModal').style.display = 'none';
        }

        function printLoanDetail() {
            // Get the printable content
            const element = document.getElementById('printableContent');
            const loanBarcode = '{{ $loan->barcode }}';
            const fileName = `Peminjaman_${loanBarcode}_${new Date().getTime()}`;
            
            // Options for html2pdf
            const options = {
                margin: 15,
                filename: fileName + '.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { format: 'a4', orientation: 'portrait' }
            };
            
            // Create wrapper div with styles
            const wrapper = document.createElement('div');
            wrapper.innerHTML = `
                <div style="font-family: 'Poppins', Arial, sans-serif; padding: 20px; background: white;">
                    ${element.innerHTML}
                </div>
            `;
            
            // Generate PDF and download
            html2pdf().set(options).from(wrapper).save();
        }

        // Auto-show modal if session has showDetailModal flag
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('showDetailModal'))
                showLoanDetailModal();
            @endif
        });

        // Close modal when clicking outside
        document.getElementById('loanDetailModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeLoanDetailModal();
            }
        });

        // Pay fine function
        function payFine() {
            const fine = calculateFine();
            if (confirm(`Bayar denda sebesar Rp ${formatNumber(fine)}?`)) {
                // Here you would typically redirect to payment page
                alert('Redirecting to payment page...');
            }
        }

        // Rate book function
        function rateBook() {
            alert('Fitur rating buku akan segera tersedia!');
        }

        // Mark as lost (admin function)
        function markAsLost() {
            if (confirm('Tandai buku ini sebagai hilang? Ini akan menambahkan denda khusus.')) {
                // Submit form to mark as lost
                fetch('{{ route("admin.loans.mark-lost", $loan) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(response => {
                    if (response.ok) {
                        alert('Buku ditandai sebagai hilang. Halaman akan direfresh.');
                        location.reload();
                    }
                });
            }
        }

        // Trigger print dialog - works like Ctrl+P
        function triggerPrint() {
            // Langsung buka dialog print seperti Ctrl+P
            window.print();
        }

        // Prepare for print/PDF (legacy - tidak digunakan lagi)
        function prepareForPrint() {
            // Langsung buka dialog print seperti Ctrl+P
            window.print();
        }

        // Auto-refresh page every 5 minutes for pending/approved loans
        @if(in_array($loan->status, ['pending', 'approved']))
        setTimeout(() => {
            location.reload();
        }, 5 * 60 * 1000);
        @endif

        // Show loading state on form submission
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<div class="loader"></div>';
                        submitBtn.disabled = true;
                    }
                });
            });
        });
    </script>
    
    <!-- HTML2PDF Library for PDF Export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    
    <script src="{{ asset('js/user-notifications.js') }}"></script>
    <script src="{{ asset('js/user-sidebar.js') }}"></script>
</body>
</html>
