@forelse($patients as $patient)
<tr>
    <td>
        <a href="{{ route('patients.show', $patient) }}">
            {{ $patient->name }}
        </a>
    </td>
    <td>{{ $patient->age }} yrs</td>
    <td>
        <span class="badge bg-{{ $patient->statusBadgeColor() }}">
            {{ ucfirst($patient->status) }}
        </span>
    </td>
    <td>{{ $patient->doctor->name }}</td>
    @if(auth()->user()->isNurse())
    <td>{{ $patient->nurse?->name ?? 'Unassigned' }}</td>
    @endif
    <td>{{ $patient->updated_at->diffForHumans() }}</td>
    <td>
        <a href="{{ route('patients.show', $patient) }}" class="btn btn-sm btn-info">
            <i class="fas fa-eye"></i>
        </a>
        @if(auth()->user()->canEditPatient($patient))
        <a href="{{ route('patients.edit', $patient) }}" class="btn btn-sm btn-primary">
            <i class="fas fa-edit"></i>
        </a>
        @endif
    </td>
</tr>
@empty
<tr>
    <td colspan="{{ auth()->user()->isNurse() ? 7 : 6 }}" class="text-center">No patients found</td>
</tr>
@endforelse
