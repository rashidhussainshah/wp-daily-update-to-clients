@extends('layouts.emails')

@section('title', $data['subject'] ?? '')


@section('content')
    <h1>Payment Request Details</h1>
    @if($data['is_payment_approve_req'])
        <h3>Congratulations, Payment request is approved</h3>
    @endif
    <table>
        <tbody>
        <tr>
            <th>Project:</th>
            <td>{{$data['project_name'] ?? ''}}</td>
        </tr>
        <tr>
            <th>Project Target:</th>
            <td>{{$data['project_target_title'] ?? ''}}</td>
        </tr>
        <tr>
            <th>Developer:</th>
            <td>{{$data['developer_name'] ?? ''}}</td>
        </tr>
        <tr>
            <th>Client Source:</th>
            <td> {{$data['client_source'] ?? ''}}</td>
        </tr>
        <tr>
            <th>Currency Current Rate:</th>
            <td>Rs {{$data['currency_current_rate'] ?? ''}}</td>
        </tr>
        <tr>
            <th>Total Earning:</th>
            <td>$ {{$data['total_earning'] ?? ''}}</td>
        </tr>
        <tr>
            <th>Developer Earning:</th>
            <td>$ {{$data['dev_earning'] ?? ''}}</td>
        </tr>
        <tr>
            <th>Payable:</th>
            <td>Rs {{$data['payable'] ?? ''}}</td>

        </tr>
        <tr>
            <th>Paid:</th>
            <td class="status paid">Rs {{$data['paid'] ?? ''}}</td>
        </tr>
        <tr>
            <th>Status:</th>
            <td>{{$data['status'] ?? ''}}</td>
        </tr>
        <tr>
            <th>Fee:</th>
            <td>{{$data['fee'] ?? ''}}</td>
        </tr>

        <tr>
            <th>{{$data['is_payment_approve_req'] ? 'Approved' : 'Requested'}} At:</th>
            <td>{{readableCurrentDate()}}</td>
        </tr>
        <tr>
            <th>Notes:</th>
            <td>{{$data['notes'] ?? ''}}</td>
        </tr>
        </tbody>
    </table>
    <a href="{{ route('voyager.user-payments.show', ['id' => $data['id']]) }}">Click here</a> to view the payment request details.
    <p>{{ getRandomQuote() }}</p>
@stop
