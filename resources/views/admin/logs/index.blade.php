@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>System Logs</h5>
                <form action="{{ url('/admin/logs/clear') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure you want to clear all logs?')">
                        <i class="fas fa-trash"></i> Clear Logs
                    </button>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th width="20%">Timestamp</th>
                            <th>Log Entry</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($logs as $log)
                            <tr class="@if(str_contains($log, 'ERROR')) table-danger @elseif(str_contains($log, 'WARNING')) table-warning @endif">
                                <td>
                                    @if(preg_match('/\[(.*?)\]/', $log, $matches))
                                        {{ $matches[1] }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <code>{{ $log }}</code>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">No logs found</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
