@extends('layouts.emails')

@section('title', $data['subject'] ?? '')


@section('content')
    <h1>Payment Request Details</h1>
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
            <th>Project Target Status:</th>
            <td>{{$data['project_target_status'] ?? ''}}</td>
        </tr>
        <tr>
            <th>Fee:</th>
            <td>{{$data['fee'] ?? ''}}</td>
        </tr>
        <tr>
            <th>Currency Current Rate:</th>
            <td>Rs {{$data['currency_current_rate'] ?? ''}}</td>
        </tr>
        <tr>
            <th>Requested At:</th>
            <td>{{readableCurrentDate()}}</td>
        </tr>
        <tr>
            <th>Notes:</th>
            <td>{{$data['notes'] ?? ''}}</td>
        </tr>
        </tbody>
    </table>
@stop
