<div class="wrapper-custom-1 col l4 m5 s12">
    <div class="datapicker-wrapper-1">
        <div class="col" style="position: relative; padding: 25px 10px 10px 30px; min-width: 9rem;"><i class="material-icons" style="top: 22px; left: 0; position: absolute;">date_range</i><span style="font-size: 11px;">{{trans('main.orders_products.table.filter.date.start')}}:</span></div>
        <div class="input-field col" style="">
            <input id="date_start_from" type="date" class="datepicker" value="" name="date_start_from" style="margin: 0;">
            <label for="date_start_from" class="input-label-1">от</label>
        </div>
        <div class="input-field col" style="">
            <input id="date_start_to" type="date" class="datepicker" value="" name="date_start_to" style="margin: 0;">
            <label for="date_start_to" class="input-label-1">до</label>
        </div>
        <div class="clear"></div>
    </div>
</div>
<div class="input-field col l2 m3 s12">
    <select multiple id="status-select" name="status">
        <option value="" disabled selected>{{trans('main.misc.any')}}</option>
        @foreach($product_statuses->where('is_p', 1) as $p_s)
            <option value="{{$p_s->id}}">{{$p_s->name}}</option>
        @endforeach
    </select>
    <label>{{trans('main.orders_products.table.filter.status')}}</label>
</div>
<div class="input-field col l2 m2 s12">
    <select class="" tabindex="-1" style="width: 50%" id="w_service-select" name="w_service">
        <option value="0">all</option>
        <option value="1">С сервисом</option>
        <option value="2">Без сервиса</option>
        <option value="3">Multiroom</option>
    </select>
    <label>{{trans('main.orders_products.table.filter.with_service')}}</label>
</div>
<div class="input-field col l1 m2 s12">
    <select class="" tabindex="-1" style="width: 50%" id="payed_status-select" name="payed_status">
        <option value="0">{{trans('main.misc.any')}}</option>
        <option value="1">оплачен</option>
        <option value="2">добавлен</option>
    </select>
    <label>{{trans('main.orders_products.table.filter.payment_status')}}</label>
</div>
<div class="input-field col l2 m3 s12" style="float: right !important;">
    <input type="search" class="expand-search pull-right" placeholder="Поиск записей" id="search_input" name="search_input">
    <div class="clear"></div>
    <!-- <label for="date_end" class="input-label-1">date end</label> -->
</div>
<div class="clear"></div>
<script type="text/javascript">
    $('select').material_select();
    var datepicker = $('.datepicker').pickadate({
        selectMonths: true,
        selectYears: 100,
        format: 'yyyy-mm-dd',
    });
    var picker = datepicker.pickadate('picker');
</script>