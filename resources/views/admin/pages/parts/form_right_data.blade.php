<div class="" >
    <ul class="collapsible" data-collapsible="expandable" style="margin: 0; box-shadow: none;">
        <li class="active" id="item_options" style="display: none;">
            <div class="collapsible-header active"><i class="material-icons">settings_applications</i>{{ trans('main.content.pages.collection_headers.settings') }}</div>
            <div class="collapsible-body">
                <form id="edit_pages_type_settings" class="edit_menu_item-form">
                    <input type="hidden" name="id" id="i_id" value="">
                    <input type="hidden" name="language_id" id="si_language_id" value="">
                    <div class="col s12">
                        <div class="col s12">
                            <a style="font-size: 16px;margin-bottom: 40px" href="" target="_blank"></a>
                        </div>
                    </div>
                    <div class="col l8 m12 s12">
                        <div class="input-field col l6 m6 s12">
                            <input id="i_name" type="text" class="validate" name="name" value="">
                            <label for="i_name">{{ trans('main.content.pages.settings.name') }}</label>
                        </div>
                        <div class="input-field col l6 m6 s12">
                            <input id="i_title" type="text" class="validate" name="title" value="" >
                            <label for="i_title">{{ trans('main.content.pages.settings.title') }}</label>
                        </div>
                    </div>
                    <div class="input-field col l1 m4 s12">
                        <span class="input-wrapper-span-1">
                            <input type="checkbox" id="i_watch" name="watch">
                            <label for="i_watch">{{ trans('main.content.pages.settings.public') }}</label>
                        </span>
                    </div>
                    <!-- <div class="">
                        <div class="input-field col l2 m4 s12">
                            <div class="col s12">
                                <a class="modal-trigger waves-effect waves-light btn-flat btn-flat-custom-1 pull-right" href="#modal1">{{ trans('main.content.pages.settings.text') }}</a>
                            </div>
                        </div>
                    </div> -->
                    
                    <div class="clear"></div>
                    <div class="row">
                        <div class="col l12"> 
                            <div class="input-field col s12">
                                <button class="waves-effect waves-light btn pull-left btn-custom-width side-menu-parameters-save-btn parameters-save-btn" id="edit_menu_item-form-save-btn" type="submit" data-reset="<i class='material-icons'>check</i> {{trans('main.buttons.save')}}"><i class='material-icons'>check</i> {{trans('main.buttons.save')}}</button>
                            </div>
                        </div>      
                    </div>
                    <div class="clear"></div>
                </form>
                <!-- Modal Structure -->
               <!--  <div id="modal1" class="modal modal-fixed-footer">
                    <div class="modal-content">
                        <div class="input-field col s12">
                            <span>{{ trans('main.content.pages.settings.text') }}</span>
                            <div id="i_description_wrapper">
                                <textarea id="i_description" class="materialize-textarea" name="description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">{{trans('main.buttons.save')}}</a>
                    </div>
                </div> -->
            </div>
        </li>
        <li>
            <div class="collapsible-header active"><i class="material-icons">view_list</i>{{ trans('main.content.pages.collection_headers.pages') }}</div>
            <div class="collapsible-body table-holder-1" style="padding:20px;">
                <div class="">
                    <a class="add_text_button-1 waves-effect waves-green btn btn-custom-wi" data-val="" style="display: none;"><i class="material-icons">library_add</i> {{trans('main.buttons.add')}}</a>
                </div>
                <div id="pages_table-container">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </li>
    </ul>
</div>
<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
<script src="{{ URL::asset('vendor/datatables/buttons.server-side.js') }}"></script>

