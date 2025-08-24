<?php include 'darkmode_auto.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Emergency Helpline - Bangladesh</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: var(--bg-primary);
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            transition: background-color 0.3s ease, color 0.3s ease;
            color: var(--text-primary);
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: var(--bg-secondary);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 32px var(--shadow);
            border: 1px solid var(--border);
            transition: all 0.3s ease;
        }
        h1 {
            color: var(--accent);
            font-size: 2.5em;
            margin-bottom: 30px;
            text-align: center;
            font-weight: bold;
        }
        .intro {
            color: var(--text-secondary);
            font-size: 1.1em;
            line-height: 1.6;
            margin-bottom: 30px;
            text-align: justify;
        }
        .section {
            margin-bottom: 30px;
            padding: 20px;
            background: var(--bg-primary);
            border-radius: 10px;
            border: 1px solid var(--border);
        }
        .section h2 {
            color: var(--accent);
            font-size: 1.8em;
            margin-bottom: 15px;
            border-bottom: 2px solid var(--accent);
            padding-bottom: 10px;
        }
        .contact-item {
            margin-bottom: 15px;
            padding: 10px;
            background: var(--bg-secondary);
            border-radius: 8px;
            border-left: 4px solid var(--accent);
        }
        .contact-name {
            font-weight: bold;
            color: var(--text-primary);
            font-size: 1.1em;
            margin-bottom: 5px;
        }
        .contact-number {
            color: var(--accent);
            font-size: 1.2em;
            font-weight: bold;
        }
        .contact-description {
            color: var(--text-secondary);
            font-size: 0.9em;
            margin-top: 5px;
            font-style: italic;
        }
        .emergency-highlight {
            background: #dc3545;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
            font-weight: bold;
            font-size: 1.2em;
        }
        .notes {
            background: var(--bg-primary);
            padding: 20px;
            border-radius: 10px;
            border: 2px solid var(--accent);
            margin-top: 30px;
        }
        .notes h3 {
            color: var(--accent);
            margin-bottom: 15px;
        }
        .back-btn {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 24px;
            background-color: var(--accent);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .back-btn:hover {
            background-color: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px var(--shadow);
        }
        .important-websites {
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--bg-secondary);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 20px var(--shadow);
            border: 1px solid var(--border);
            max-width: 250px;
            z-index: 1000;
        }
        .important-websites h3 {
            color: var(--accent);
            margin: 0 0 15px 0;
            font-size: 1.2em;
            text-align: center;
            border-bottom: 2px solid var(--accent);
            padding-bottom: 8px;
        }
        .website-link {
            display: block;
            color: var(--text-primary);
            text-decoration: none;
            padding: 8px 12px;
            margin-bottom: 8px;
            border-radius: 6px;
            background: var(--bg-primary);
            border: 1px solid var(--border);
            transition: all 0.3s ease;
            font-size: 0.9em;
        }
        .website-link:hover {
            background: var(--accent);
            color: white;
            transform: translateX(5px);
            box-shadow: 0 2px 10px var(--shadow);
        }
        @media (max-width: 768px) {
            .container {
                padding: 20px;
                margin: 10px;
            }
            h1 {
                font-size: 2em;
            }
            .section h2 {
                font-size: 1.5em;
            }
            .important-websites {
                position: relative;
                top: auto;
                right: auto;
                margin: 0 0 30px 0;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="important-websites">
        <h3>Important Websites</h3>
        <a href="https://bangladeshhealthalliance.com/ambulances/" target="_blank" class="website-link">
            🚑 Ambulance Numbers
        </a>
        <a href="https://fireservice.gov.bd/site/page/7676b3e3-aa06-4214-91b9-17d4cf042b4e/%E0%A6%B8%E0%A6%95%E0%A6%B2-%E0%A6%B8%E0%A7%8D%E0%A6%9F%E0%A7%87%E0%A6%B6%E0%A6%A8%E0%A7%87%E0%A6%B0-%E0%A6%A8%E0%A6%AE%E0%A7%8D%E0%A6%AC%E0%A6%B0" target="_blank" class="website-link">
            🔥 Fire Service Numbers
        </a>
        <a href="https://www.abohomanbangla.com/Bangladesh_web_directory/bangladesh_police_contact_number.html" target="_blank" class="website-link">
            👮 Police Numbers
        </a>
        <a href="https://ispr.gov.bd/%e0%a6%b8%e0%a7%87%e0%a6%a8%e0%a6%be%e0%a6%ac%e0%a6%be%e0%a6%b9%e0%a6%bf%e0%a6%a8%e0%a7%80%e0%a6%b0-%e0%a6%95%e0%a7%8d%e0%a6%af%e0%a6%be%e0%a6%ae%e0%a7%8d%e0%a6%aa%e0%a6%b8%e0%a6%ae%e0%a7%82%e0%a6%b9/" target="_blank" class="website-link">
            🎖️ Army Numbers
        </a>
    </div>

    <div class="container">
        <h1>🚨 Emergency Helpline - Bangladesh</h1>
        
        <div class="intro">
            Here is a comprehensive list of crucial emergency contact numbers in Bangladesh to help you quickly reach essential services in urgent situations.
        </div>

        <div class="emergency-highlight">
            🚨 NATIONAL EMERGENCY: 999 🚨
        </div>

        <div class="section">
            <h2>National Emergency Numbers</h2>
            <div class="contact-item">
                <div class="contact-name">National Emergency Number</div>
                <div class="contact-number">999</div>
                <div class="contact-description">Police, Ambulance & Fire Service</div>
            </div>
            <div class="contact-item">
                <div class="contact-name">Fire Service Department</div>
                <div class="contact-number">102</div>
                <div class="contact-description">Primary number for fire emergencies</div>
            </div>
            <div class="contact-item">
                <div class="contact-name">Tourist Police</div>
                <div class="contact-number">+8801320222222</div>
                <div class="contact-description">For assistance to tourists</div>
            </div>
        </div>

        <div class="section">
            <h2>Police Contacts</h2>
            <div class="contact-item">
                <div class="contact-name">Control Room</div>
                <div class="contact-number">8616552-7, 8616551-3, 8914664</div>
            </div>
            <div class="contact-item">
                <div class="contact-name">Detective Branch (DC, DB)</div>
                <div class="contact-number">9337362</div>
            </div>
            <div class="contact-item">
                <div class="contact-name">Emergency Police Number</div>
                <div class="contact-number">999</div>
            </div>
            <div class="contact-item">
                <div class="contact-name">Regional Police Contacts</div>
                <div class="contact-description">For specific police stations or regional areas, officer contacts can be reached via various mobile numbers starting with +8801713XXXXXX for Dhaka metropolitan areas among others (detailed listings available online for every police station in Bangladesh)</div>
            </div>
        </div>

        <div class="section">
            <h2>Ambulance Services in Dhaka</h2>
            <div class="contact-item">
                <div class="contact-name">Tasfi Ambulance Service</div>
                <div class="contact-number">01740-213242</div>
                <div class="contact-description">24/7 Service</div>
            </div>
            <div class="contact-item">
                <div class="contact-name">Razu Ambulance Service</div>
                <div class="contact-number">01989-990070</div>
                <div class="contact-description">24/7 Service</div>
            </div>
            <div class="contact-item">
                <div class="contact-name">Ambulance BD 24</div>
                <div class="contact-number">01919-339689</div>
                <div class="contact-description">09:00 AM – 05:00 PM</div>
            </div>
            <div class="contact-item">
                <div class="contact-name">Dhaka Ambulance Service</div>
                <div class="contact-number">01757-161571</div>
            </div>
        </div>

        <div class="section">
            <h2>Fire Service</h2>
            <div class="contact-item">
                <div class="contact-name">Fire Service Emergency</div>
                <div class="contact-number">102</div>
                <div class="contact-description">Primary number for fire emergencies</div>
            </div>
            <div class="contact-item">
                <div class="contact-name">Former Number (Deprecated)</div>
                <div class="contact-number">16163</div>
                <div class="contact-description">Deprecated as of January 1, 2025</div>
            </div>
        </div>

        <div class="section">
            <h2>Hospitals & Specialized Services</h2>
            <div class="contact-item">
                <div class="contact-name">National Heart Foundation Hospital</div>
                <div class="contact-number">01903-344262</div>
            </div>
            <div class="contact-item">
                <div class="contact-name">Other Hospitals & Blood Banks</div>
                <div class="contact-description">Various hospitals and blood banks are available in major cities with contact info online for specialized care and blood donation</div>
            </div>
        </div>

        <div class="section">
            <h2>Additional Helplines</h2>
            <div class="contact-item">
                <div class="contact-name">National Helpline for Violence Against Women and Children</div>
                <div class="contact-number">109</div>
            </div>
            <div class="contact-item">
                <div class="contact-name">Sexual and Reproductive Health Helpline</div>
                <div class="contact-number">08000222333</div>
            </div>
        </div>

        <div class="notes">
            <h3>📞 Notes for Calling from Abroad</h3>
            <p>Country code for Bangladesh is <strong>+880</strong>, so for mobile numbers add +880 before the local mobile number omitting the leading '0'. For example, Tasfi Ambulance Service from abroad would be <strong>+8801740213242</strong>.</p>
            
            <h3>📋 Important Information</h3>
            <p>These emergency contacts cover the most critical services needed in various emergency scenarios across Bangladesh, including Dhaka. It's recommended to save these numbers or keep them handy for immediate access in case of need.</p>
            
            <p>For a more detailed directory (including regional police contacts and hospitals), official government and authorized websites maintain updated and specific contact lists.</p>
            
            <p><strong>Stay safe!</strong></p>
        </div>

        <a href="javascript:history.back()" class="back-btn">← Go Back</a>
    </div>
</body>
</html>
