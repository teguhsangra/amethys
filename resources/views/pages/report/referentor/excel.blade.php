<table class="table table-bordered text-center" id="referral_achievment_table">
    <thead>
        <tr>
        @foreach($referrals as $detail)
            <th>{{ $detail->name }}</th>
        @endforeach
        </tr>
    </thead>
    <tbody>
        <tr>
            @php
                $all_referral_achievement = 0;
            @endphp
            @foreach($referrals as $no => $detail)
                @php
                    $all_referral_achievement = $all_referral_achievement + $referral_achievement[$no];
                @endphp
                <td>{{ number_format($referral_achievement[$no], 0, ',', '.') }}</td>
            @endforeach
        </tr>
        <tr>
            <td colspan="{{ sizeof($referrals) }}">{{ number_format($all_referral_achievement, 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>
<table class="table table-bordered text-center" id="agent_achievment_table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Achievement</th>
        </tr>
    </thead>
    <tbody>
        @php
            $all_agent_achievement = 0;
        @endphp
        @foreach($agents as $no => $detail)
            @php
                $all_agent_achievement = $all_agent_achievement + $agent_achievement[$no];
            @endphp
            <tr>
                <td>{{ $detail->name }}</td>
                <td>{{ number_format($all_agent_achievement[$no], 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td>Total</td>
            <td>{{ number_format($all_agent_achievement, 0, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>