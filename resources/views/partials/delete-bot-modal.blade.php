<div class="modal fade" id="delete-bot-{{ $bot->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4>
                    Delete trading bot {{ $bot->formattedId() }}?
                </h4>
                <p>If this bot has an open buy order, a new sell order will be placed</p>
                <form action="{{ route('bots.delete', ['bot' => $bot->id]) }}" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn pull-right bg-red">Delete</button>
                            <button class="btn btn-cancel pull-right" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>