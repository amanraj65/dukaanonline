<!-- chatbot.php -->
<style>
/* Floating Chatbot Button */
#chatbot-btn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: linear-gradient(135deg, #007bff, #00d4ff);
    color: white;
    border: none;
    padding: 15px;
    border-radius: 50%;
    font-size: 22px;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s;
}

#chatbot-btn:hover {
    transform: scale(1.1);
}

/* Chatbot Window */
#chatbot-window {
    position: fixed;
    bottom: 80px;
    right: 20px;
    width: 320px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    display: none;
    flex-direction: column;
    overflow: hidden;
    font-family: Arial, sans-serif;
}

/* Chat Header */
.chat-header {
    background: #007bff;
    color: white;
    padding: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: bold;
}

.chat-header button {
    background: none;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
}

/* Chat Body */
.chat-body {
    padding: 10px;
    height: 300px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
}

/* Chat Messages */
.chat-message {
    padding: 8px 12px;
    border-radius: 15px;
    margin-bottom: 10px;
    max-width: 80%;
}

.bot {
    background: #f1f1f1;
    align-self: flex-start;
}

.user {
    background: #007bff;
    color: white;
    align-self: flex-end;
}

/* Chat Footer */
.chat-footer {
    display: flex;
    padding: 10px;
    border-top: 1px solid #ddd;
}

.chat-footer input {
    flex: 1;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.chat-footer button {
    background: #007bff;
    color: white;
    border: none;
    padding: 8px;
    margin-left: 5px;
    cursor: pointer;
    border-radius: 5px;
}
</style>

<!-- Floating Chatbot Button -->
<button id="chatbot-btn">
    💬
</button>

<!-- Chatbot Window -->
<div id="chatbot-window">
    <div class="chat-header">
        <span>Chat with Us</span>
        <button id="close-chat">&times;</button>
    </div>
    <div class="chat-body" id="chat-body">
        <div class="chat-message bot">Hello! How can I help you?</div>
    </div>
    <div class="chat-footer">
        <input type="text" id="user-input" placeholder="Type a message...">
        <button id="send-btn">➤</button>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const chatbotBtn = document.getElementById("chatbot-btn");
    const chatbotWindow = document.getElementById("chatbot-window");
    const closeChat = document.getElementById("close-chat");
    const sendBtn = document.getElementById("send-btn");
    const userInput = document.getElementById("user-input");
    const chatBody = document.getElementById("chat-body");

    // Load Chat History from localStorage
    function loadChatHistory() {
        const chatHistory = localStorage.getItem("chatHistory");
        if (chatHistory) {
            chatBody.innerHTML = chatHistory;
            chatBody.scrollTop = chatBody.scrollHeight;
        }
    }

    // Save Chat History to localStorage
    function saveChatHistory() {
        localStorage.setItem("chatHistory", chatBody.innerHTML);
    }

    // Toggle Chatbot Window
    chatbotBtn.addEventListener("click", function () {
        chatbotWindow.style.display = "flex";
        loadChatHistory(); // Load chat history when chatbot opens
    });

    closeChat.addEventListener("click", function () {
        chatbotWindow.style.display = "none";
    });

    // Send Message Function
    function sendMessage() {
        let message = userInput.value.trim();
        if (message === "") return;

        // Display User Message
        let userMessage = document.createElement("div");
        userMessage.className = "chat-message user";
        userMessage.innerText = message;
        chatBody.appendChild(userMessage);

        userInput.value = "";
        chatBody.scrollTop = chatBody.scrollHeight;
        saveChatHistory(); // Save updated history

        // Display "AI is thinking..." message
        let loadingMessage = document.createElement("div");
        loadingMessage.className = "chat-message bot loading";
        loadingMessage.innerText = "AI is thinking...";
        chatBody.appendChild(loadingMessage);
        chatBody.scrollTop = chatBody.scrollHeight;
        saveChatHistory();

        // Send to Backend
        fetch("chatbot-backend.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "message=" + encodeURIComponent(message),
        })
        .then(response => response.text())
        .then(data => {
            chatBody.removeChild(loadingMessage); // Remove "AI is thinking..."

            let botMessage = document.createElement("div");
            botMessage.className = "chat-message bot";
            botMessage.innerText = data;
            chatBody.appendChild(botMessage);
            chatBody.scrollTop = chatBody.scrollHeight;
            saveChatHistory(); // Save updated history
        })
        .catch(error => {
            chatBody.removeChild(loadingMessage); // Remove "AI is thinking..." if error occurs

            let errorMessage = document.createElement("div");
            errorMessage.className = "chat-message bot error";
            errorMessage.innerText = "Error: Unable to get a response. Please try again.";
            chatBody.appendChild(errorMessage);
            chatBody.scrollTop = chatBody.scrollHeight;
            saveChatHistory(); // Save updated history
        });
    }

    // Load chat history when the page is loaded
    loadChatHistory();

    // Event Listeners
    sendBtn.addEventListener("click", sendMessage);
    userInput.addEventListener("keypress", function (event) {
        if (event.key === "Enter") sendMessage();
    });
});
</script>
