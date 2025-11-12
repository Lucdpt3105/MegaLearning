# MegaLearning Chat API Test Script
Write-Host "🚀 Testing MegaLearning Chat API..." -ForegroundColor Cyan
Write-Host ""

$baseUrl = "http://localhost:8000/api/chat"

# Test 1: Get all rooms
Write-Host "📋 Test 1: Getting all rooms..." -ForegroundColor Yellow
try {
    $response = Invoke-RestMethod -Uri "$baseUrl/rooms" -Method Get
    if ($response.success) {
        Write-Host "✅ Success! Found $($response.data.Count) rooms" -ForegroundColor Green
        $response.data | ForEach-Object {
            Write-Host "  - Room ID: $($_.room_id), Name: $($_.room_name), Type: $($_.room_type)" -ForegroundColor Gray
        }
    }
} catch {
    Write-Host "❌ Error: $_" -ForegroundColor Red
}
Write-Host ""

# Test 2: Create a new room
Write-Host "📋 Test 2: Creating a new test room..." -ForegroundColor Yellow
try {
    $roomData = @{
        room_name = "PowerShell Test Room $(Get-Date -Format 'HH:mm:ss')"
        room_type = "group"
        include_ai = $false
    } | ConvertTo-Json

    $response = Invoke-RestMethod -Uri "$baseUrl/rooms" -Method Post -Body $roomData -ContentType "application/json"
    
    if ($response.success) {
        $roomId = $response.data.room_id
        Write-Host "✅ Room created! ID: $roomId" -ForegroundColor Green
        Write-Host "  Name: $($response.data.room_name)" -ForegroundColor Gray
        
        # Test 3: Send message to the new room
        Write-Host ""
        Write-Host "📋 Test 3: Sending a message..." -ForegroundColor Yellow
        
        $messageData = @{
            message_text = "Hello from PowerShell! 🚀 Testing chat system."
        } | ConvertTo-Json
        
        $msgResponse = Invoke-RestMethod -Uri "$baseUrl/rooms/$roomId/messages" -Method Post -Body $messageData -ContentType "application/json"
        
        if ($msgResponse.success) {
            Write-Host "✅ Message sent successfully!" -ForegroundColor Green
            Write-Host "  Message ID: $($msgResponse.data.message_id)" -ForegroundColor Gray
            Write-Host "  Text: $($msgResponse.data.message_text)" -ForegroundColor Gray
        }
        
        # Test 4: Get messages from room
        Write-Host ""
        Write-Host "📋 Test 4: Getting messages from room..." -ForegroundColor Yellow
        
        Start-Sleep -Seconds 1
        $messagesResponse = Invoke-RestMethod -Uri "$baseUrl/rooms/$roomId/messages" -Method Get
        
        if ($messagesResponse.success) {
            Write-Host "✅ Found $($messagesResponse.data.data.Count) messages" -ForegroundColor Green
            $messagesResponse.data.data | ForEach-Object {
                Write-Host "  - [$($_.user.name)]: $($_.message_text)" -ForegroundColor Gray
            }
        }
    }
} catch {
    Write-Host "❌ Error: $_" -ForegroundColor Red
}

Write-Host ""
Write-Host "🎉 All tests completed!" -ForegroundColor Cyan
Write-Host ""
Write-Host "💡 Tips:" -ForegroundColor Yellow
Write-Host "  - Open http://localhost:8000/chat-test.html in your browser" -ForegroundColor Gray
Write-Host "  - Try creating rooms and sending messages" -ForegroundColor Gray
Write-Host "  - Check the database for saved data" -ForegroundColor Gray
Write-Host ""
