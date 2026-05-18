document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.getElementById("ai-chatbot-toggle");
    const chatBox = document.getElementById("ai-chatbot-box");
    const closeBtn = document.getElementById("ai-chatbot-close");
    const sendBtn = document.getElementById("ai-chatbot-send");
    const input = document.getElementById("ai-chatbot-input");
    const messages = document.getElementById("ai-chatbot-messages");

    let chatHistory = [];

    if (!toggleBtn || !chatBox || !closeBtn || !sendBtn || !input || !messages) {
        return;
    }

    toggleBtn.addEventListener("click", function () {
        chatBox.classList.add("active");
        toggleBtn.style.display = "none";
    });

    closeBtn.addEventListener("click", function () {
        chatBox.classList.remove("active");
        toggleBtn.style.display = "flex";
    });

    sendBtn.addEventListener("click", sendMessage);

    input.addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
            sendMessage();
        }
    });

    function sendMessage() {
        const userMessage = input.value.trim();

        if (userMessage === "") {
            return;
        }

        addMessage(userMessage, "user");
        input.value = "";

        chatHistory.push({
            role: "user",
            content: userMessage
        });

        const loadingMessage = addMessage("Thinking...", "bot");

        fetch("/cleare/actions/chatbot.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body:
                "message=" + encodeURIComponent(userMessage) +
                "&history=" + encodeURIComponent(JSON.stringify(chatHistory))
        })
           .then(function (response) {
    return response.text();
})
.then(function (text) {
    console.log("AI chatbot raw response:", text);

    let data;

    try {
        data = JSON.parse(text);
    } catch (error) {
        loadingMessage.textContent = "Invalid server response. Check console.";
        return;
    }

    loadingMessage.textContent = data.reply;

    chatHistory.push({
        role: "assistant",
        content: data.reply
    });

    if (chatHistory.length > 10) {
        chatHistory = chatHistory.slice(-10);
    }
})
.catch(function (error) {
    console.error("AI chatbot fetch error:", error);
    loadingMessage.textContent = "Something went wrong. Please try again.";
});
    }

    function addMessage(text, sender) {
        const messageDiv = document.createElement("div");

        messageDiv.classList.add("ai-chat-message");

        if (sender === "user") {
            messageDiv.classList.add("ai-user-message");
        } else {
            messageDiv.classList.add("ai-bot-message");
        }

        messageDiv.textContent = text;
        messages.appendChild(messageDiv);
        messages.scrollTop = messages.scrollHeight;

        return messageDiv;
    }
});