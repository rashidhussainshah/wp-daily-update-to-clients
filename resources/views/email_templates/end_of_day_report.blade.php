@extends('layouts.emails')

@section('title', $subject ? $subject. readableCurrentDate() : 'Daily Status Report'. readableCurrentDate())


@section('content')

    <!-- Header section ends here and info section starts from here -->


    <table style="font-family: arial, helvetica, sans-serif" role="presentation"
           cellpadding="0" cellspacing="0" width="100%" border="0">
        <tbody>
        <tr>
            <td style="
                                  overflow-wrap: break-word;
                                  word-break: break-word;
                                  padding: 10px;
                                  font-family: arial, helvetica, sans-serif;
                                " align="left">
                <div>
                    <div class="content">

                        <div class="task-section" style="
                                        background-color: rgb(255, 255, 255);
                                        width: 100%;
                                        padding-bottom: 45px;
                                      ">


                            <div class="task-box">
                                <h2 style="
                 text-align: left;
    margin-left: 53px;
    font-size: 18px;
    font-family: arial;
    font-weight: 300;
    text-transform: uppercase;
    padding: 21px 0 0px;
    color: rgb(84, 85, 84);
    margin-bottom: 0px;
                                          ">
                                    Daily Tasks Updates
                                </h2>
                                <hr class="h2-hr" style="
                                 background-color: rgb(70, 63, 63);
    height: 0.5px;
    clear: both;
    align-content: flex-start;
    margin: auto;
    align-items: flex-start;
                                          ">
                                {!! $eodHtmlTemplate ? $eodHtmlTemplate : '' !!}
                            </div>
                            <!-- <hr style="
background-color: rgb(70, 63, 63);
height: 2px;
clear: both;
width: 95%;
margin: auto;
"> -->

                        </div>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
@stop
