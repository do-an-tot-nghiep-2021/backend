<table>
    <thead>
        <tr>
            <th>Tên khách hàng</th>
            <th>Số điện thoại</th>
            <th>Email</th>
            <th>Địa chỉ giao hàng</th>
            <th>Sản phẩm</th>
            <th>Tổng tiền</th>
            <th>Phương thức thanh toán</th>
            <th>Note</th>
            <th>Ngày đặt</th>
            <th>Trạng thái</th>
        </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr>
            <td>{{$order->user['name']}}</td>
            <td>{{$order->user['phone']}}</td>
            <td>{{$order->user['email']}}</td>
            <td>Phòng {{$order->class_name}}, {{$order->building_name}} </td>
            <td>{{$order->item_total}}</td>
            <td>{{$order->price_total}}</td>
            <td>{{$order->payment}}</td>
            <td>{{$order->note}}</td>
            <td>{{$order->time_create}}, {{$order->date_create}}</td>
            <td>{{$order->status}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
