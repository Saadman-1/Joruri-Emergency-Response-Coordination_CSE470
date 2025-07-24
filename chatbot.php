<!DOCTYPE html>
<html>
<head>
    <title>Joruri Chatbot</title>
    <style>
        body {
            background: #f4f4f4;
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .chat-container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 400px;
            padding: 30px 20px 20px 20px;
            display: flex;
            flex-direction: column;
        }
        .chat-box {
            height: 320px;
            overflow-y: auto;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background: #fafcff;
        }
        .chat-message {
            margin-bottom: 12px;
        }
        .bot {
            color: #007bff;
        }
        .user {
            color: #333;
            text-align: right;
        }
        .options {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 10px;
        }
        .option-btn {
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 8px 16px;
            cursor: pointer;
            font-size: 1em;
            transition: background 0.2s;
        }
        .option-btn:hover {
            background: #0056b3;
        }
        .input-row {
            display: flex;
            gap: 8px;
        }
        .input-row input {
            flex: 1;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .input-row button {
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            background: #007bff;
            color: #fff;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="chat-container">
    <div class="chat-box" id="chatBox"></div>
    <div class="options" id="options"></div>
    <div class="input-row" id="inputRow" style="display:none;">
        <input type="text" id="userInput" placeholder="Type your message...">
        <button onclick="sendText()">Send</button>
    </div>
</div>
<script>
const chatBox = document.getElementById('chatBox');
const optionsDiv = document.getElementById('options');
const inputRow = document.getElementById('inputRow');

function addMessage(text, sender='bot') {
    const msg = document.createElement('div');
    msg.className = 'chat-message ' + sender;
    msg.innerText = text;
    chatBox.appendChild(msg);
    chatBox.scrollTop = chatBox.scrollHeight;
}

function showOptions(opts) {
    optionsDiv.innerHTML = '';
    opts.forEach(opt => {
        const btn = document.createElement('button');
        btn.className = 'option-btn';
        btn.innerText = opt.label;
        btn.onclick = () => opt.action();
        optionsDiv.appendChild(btn);
    });
}

function startChat() {
    addMessage("Hello! I am Joruri, your emergency assistant. Please stay calm. Can you tell me what situation you are facing?");
    showOptions([
        {label: "Flood", action: () => handleSituation('flood')},
        {label: "Fire", action: () => handleSituation('fire')},
        {label: "Need of Blood", action: () => handleSituation('blood')},
        {label: "Sick", action: () => handleSituation('sick')},
        {label: "Under Threat", action: () => handleSituation('threat')},
        {label: "Medical Emergency", action: () => handleSituation('medical')},
        {label: "Other Calamity", action: () => handleSituation('other')}
    ]);
}

function handleSituation(type) {
    let response = "";
    switch(type) {
        case 'flood':
            response = "Thank you for sharing. Please move to higher ground if possible. Help is being arranged for you. Stay calm, you are not alone.";
            break;
        case 'fire':
            response = "Stay low to avoid smoke and leave the area if you can do so safely. Emergency services are being notified. Remain calm, help is on the way.";
            break;
        case 'blood':
            response = "We understand your need for blood. Your request is being prioritized and a donor will be arranged as soon as possible. Please stay strong.";
            break;
        case 'sick':
            response = "I'm sorry to hear you're not feeling well. Medical help is being arranged for you. Please rest and keep calm, assistance is on the way.";
            break;
        case 'threat':
            response = "Your safety is our priority. Please try to stay in a secure place. Authorities are being alerted to your situation. Stay calm, help is coming.";
            break;
        case 'medical':
            response = "Medical emergency noted. Please try to stay calm and follow any first aid you know. Medical professionals are being dispatched to your location.";
            break;
        case 'other':
            response = "Thank you for letting us know. Your situation is important to us. Please describe your calamity, and we will do our best to assist you.";
            showTextInput();
            return;
    }
    addMessage(response, 'bot');
    showOptions([
        {label: "Thank you", action: () => addMessage("You're welcome. Remember, you are not alone. Help is on the way.", 'bot')}
    ]);
}

function showTextInput() {
    optionsDiv.innerHTML = '';
    inputRow.style.display = '';
    document.getElementById('userInput').focus();
}

function sendText() {
    const val = document.getElementById('userInput').value.trim();
    if (!val) return;
    addMessage(val, 'user');
    inputRow.style.display = 'none';
    // Always respond positively and calmly
    setTimeout(() => {
        addMessage("Thank you for sharing. Your situation is being reviewed and help will be arranged as soon as possible. Please stay calm and safe.", 'bot');
        showOptions([
            {label: "Thank you", action: () => addMessage("You're welcome. Remember, you are not alone. Help is on the way.", 'bot')}
        ]);
    }, 700);
}

startChat();
</script>
</body>
</html>
