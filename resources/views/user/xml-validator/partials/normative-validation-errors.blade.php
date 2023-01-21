<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h3 class="text-primary font-size-18">{{ $nameBlock }}</h3>
                <div class="d-sm-flex flex-wrap">
                    <h4 class="card-title mt-3 mb-3">Помилки валідації . Кількість помилок: <span class="text-danger">{{ count($errors) }}</span>
                    </h4>
                </div>
                <table id="datatable-buttons" class="datatable-buttons table table-bordered dt-responsive nowrap w-100">
                    <thead>
                    <tr>
                        <th>Код помилки</th>
                        <th>Зміст</th>
                        <th>Чи критична</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($errors as $error)
                        <tr>
                            <td>{{ $error['type'] }}</td>
                            <td>{{ $error['text'] }}</td>
                            <td>{{ $error['isCritical'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
