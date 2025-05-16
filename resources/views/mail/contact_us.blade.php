<div>
    <p>A customer has posted a query. They are as follows:</p>
    
    <div style="margin-top: 10px;">
        <ul style="list-style: none; margin: 0; padding: 0;">
            <li style="margin-bottom: 10px;">
                <span>Name:</span>
                <span style="margin-right: 15px;">{{ $data['full_name'] }}</span>
            </li>
            <li style="margin-bottom: 10px;">
                <span>Email:</span>
                <span style="margin-right: 15px;">{{ $data['email'] }}</span>
            </li>
        </ul>

        <div style="margin-top: 15px;">
            Here's the message
            <p>{{ $data['message'] }}</p>
        </div>
    </div>
</div>
