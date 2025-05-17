@foreach($recentPatients as $patient)
<tr>
    <td>{{ $patient->name }}</td>
    <td>{{ $patient->age }} yrs</td>
    <td>{{ $patient->status }}</td>
    <td>{{ $patient->doctor->name }}</td>
    <td>{{ $patient->updated_at->diffForHumans() }}</td>
    <td>
        <a href="{{ route('patients.show', $patient) }}" class="btn btn-sm btn-info">
            <i class="fas fa-eye"></i>
        </a>
    </td>
</tr>
@endforeach
