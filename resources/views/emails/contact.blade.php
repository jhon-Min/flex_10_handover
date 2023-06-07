<style>
    div{
        font-family: verdana;
    }

    th,td{
        text-align: left;
    }
</style>
<div>
<h3>Hello, Administrator</h3>
<p>New Enquiry received from <strong>{{$contact->first_name}}</strong></p>
<table width="50%">
    <tr>
        <th>Name</th>
        <td>{{$contact->first_name}} {{$contact->last_name}}</td>
    </tr>
    <tr>
        <th>Company</th>
        <td>{{$contact->company}}</td>
    </tr>
    <tr>
        <th>Mobile</th>
        <td>{{$contact->mobile}}</td>
    </tr>
    <tr>
        <th>Email</th>
        <td>{{$contact->email}}</td>
    </tr>
    <tr>
        <th>Message</th>
        <td>{{$contact->message}}</td>
    </tr>
</table>
</div>