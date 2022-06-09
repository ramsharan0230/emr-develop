<section>

    <div style="margin-top: 2rem">
        @if(isset($signatures) && isset($signatures['left']) && count($signatures['left']))
            <div style="width: 30%; margin-left: 2rem; float: left">
                @forelse($signatures['left'] as $sig)

                    @if($sig['image'] == null)
                        <p style="margin-top: 106px">_________________________</p>
                    @else
                        <img class="" style="width: 90%;" src="data:image/jpg;base64,{{ $sig['image'] }}" alt="">
                        <p style="margin-top: 0; padding-top: 0;">_________________________</p>
                    @endif

                    <p>Reported By : {{ $sig['name'] }}</p>
                    @if($sig['designation'])
                        <p>{{ $sig['designation'] }}</p>
                    @endif
                    @if($sig['nmc'])
                        <p>{{ $sig['nmc'] }}</p>
                    @endif
                    @if($sig['nhbc'])
                        <p>{{ $sig['nhbc'] }}</p>
                    @endif
                @empty
                @endforelse
            </div>
        @endif
        @if(isset($signatures) && isset($signatures['middle']) && count($signatures['middle']))
            <div style="width: 30%; margin-right: 2rem; float: left;">
                @forelse($signatures['middle'] as $sig)

                    @if($sig['image'] == null)
                        <p style="margin-top: 106px">_________________________</p>
                    @else
                        <img class="" style="width: 90%;" src="data:image/jpg;base64,{{ $sig['image'] }}" alt="">
                        <p style="margin-top: 0; padding-top: 0;">_________________________</p>
                    @endif
                    <p>{{ $sig['name'] }}</p>
                    @if($sig['designation'])
                        <p>{{ $sig['designation'] }}</p>
                    @endif
                    @if($sig['nmc'])
                        <p>{{ $sig['nmc'] }}</p>
                    @endif
                    @if($sig['nhbc'])
                        <p>{{ $sig['nhbc'] }}</p>
                    @endif
                @empty
                @endforelse

            </div>
        @endif
        @if(isset($signatures) && isset($signatures['right']) && count($signatures['right']))
            <div style="width: 30%; margin-right: 2rem; float: left">
                @forelse($signatures['right'] as $sig)

                    @if($sig['image'] == null)
                        <p style="margin-top: 106px">_________________________</p>
                    @else
                        <img class="" style="width: 90%;" src="data:image/jpg;base64,{{ $sig['image'] }}" alt="">
                        <p style="margin-top: 0; padding-top: 0;">_________________________</p>
                    @endif
                    <p>Verified By : {{ $sig['name'] }}</p>
                    @if($sig['designation'])
                        <p>{{ $sig['designation'] }}</p>
                    @endif
                    @if($sig['nmc'])
                        <p>{{ $sig['nmc'] }}</p>
                    @endif
                    @if($sig['nhbc'])
                        <p>{{ $sig['nhbc'] }}</p>
                    @endif
                @empty
                @endforelse
            </div>
        @endif
    </div>
    <div style="clear: both"></div>

</section>
