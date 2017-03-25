<link rel="stylesheet" type="text/css" href="{{ asset('bookmarklet/css/main.css')  }}">

<div class="addon-wrapper _addon-wrapper">

    <div class="addon-alert _alert-shop-credible ">
        <strong>QUÝ KHÁCH VUI LÒNG KHÔNG SỬ DỤNG GOOGLE TRANSLATE KHI CLICK VÀO NÚT ĐẶT HÀNG</strong>
    </div>

    <div class="addon-content">
        <div class="addon-block">
            <ul class="addon-list-inline">

                <li style="display: none">
                    <a id="_add-to-favorite" href="javascript:void(0)" class="save-product-ao"> Lưu sản phẩm </a>
                </li>

                <li style="display: none">
                    <label>
                        <input type="checkbox" name="is_translate" class="_is_translate"> <span></span>
                        Dịch tự động
                    </label>
                </li>

                <li style="display: none;">
                    <ul>
                        <li class="pos-relative">
                            <div class="opt-select-ao">
                                <span class="arr-ao"></span>
                                <select class="form-control _select_category">
                                    <option>Chọn danh mục</option>
                                </select>
                            </div>

                            <div class="category-other _category-other hidden">
                                <div class="addon_arrow_box">
                                    <label class="addon-category-label">Danh mục khác: </label>
                                    <input type="text" class="form-control _input_category addon-input-category" placeholder="Tự nhập danh mục">
                                </div>
                            </div>
                        </li>
                        <li style="display: none">
                            <input class="form-control _brand_item" value="" placeholder="Nhập thương hiệu">
                        </li>
                    </ul>
                </li>
                <li>

                    <a href="javascript:void(0)" class="btn btn-danger _addToCart btn-seudo">
                        ĐẶT HÀNG NHATMINH247
                    </a>

                    <a href="{{ url('gio-hang')  }}" target="_blank" class="addon-link _link-detail-cart">
                        <i class="addon-icon-shopping-cart"></i>
                        Vào giỏ hàng
                    </a>
                    &nbsp;&nbsp;&nbsp;

                    <a href="javascript:" class="_close_tool open-more hidden">
                        Mở rộng
                    </a>

                </li>

                <li class="hidden">
                    <h3 class="addon-title">CÔNG CỤ ĐẶT HÀNG</h3>
                    <p class="addon-version _addon-version">1.0</p>
                </li>

                <li class="hidden">
                    <h6 class="addon-exchange-label">Tỉ giá</h6>
                    <p class="addon-exchange-text _addon-exchange-text">{{ App\Util::formatNumber($exchange_rate)  }}</p>
                </li>
            </ul>

        </div>
    </div>

</div>

<div class="div-block-price-book _div-block-price-book" id="li_sd_price">

    <a href="javascript:" class="_minimize_tool addon-minimize-tool">
        Thu gọn
    </a>

    <h1 class="addon-lg-title">Công Cụ Đặt Hàng</h1>

    <div class="seu-note-book" id="_box_input_exception">

        <div class="note-item">
            <span>Giá:</span>
            <p>
                <input type="text" id="_price" class="form-control" placeholder="Giá" />
            </p>
        </div>

        <div class="note-item">
            <span>Thuộc tính:</span>
            <p>
                <textarea class="addon-width-full form-control" rows="3" id="_properties" placeholder="Nhập màu sắc, kích thước VD:Màu đen; Size 41" name="_properties"></textarea>
            </p>
        </div>

        <div class="note-item">
            <span>Số lượng:</span>
            <p>
                <input type="text" class="form-control" id="_quantity" placeholder="Số lượng" />
            </p>
        </div>
    </div>

    <div class="_div_category" style="display: none;">

        <p>Chọn danh mục <span class=text-danger">*</span>: </p>

        <select data-loaded="0" class="_select_category form-control" style="width: 100%;">
            <option value="0">Chọn danh mục</option>
        </select>

        <input placeholder="Tự nhập danh mục" class="form-control _input_category addon-margin-top-10" style="display: none; width: 100%;" />

        <p class="addon-lbl-th">Thương Hiệu: </p>

        <input type="text" placeholder="Nhập thương hiệu của sản phẩm" class="form-control _brand_item" style="width: 100%;" />
    </div>

    <div class="note-text" style="display: none;">
        <p>Chú thích: </p>
        <textarea cols="60" class="form-control _comment_item" placeholder="Chú thích cho sản phẩm" name="_comment_item"></textarea>
    </div>

    <div class="xbTipBlock add-book">
        <div class="add-button" id="block_button_sd">
            <!--<button class="_addToCart btnAddToCart" type="button"></button>-->

            <button class="_addToCart btn btn-danger text-uppercase btnAddToCart" style="background: #000;border: none;padding: 5px 10px;cursor: pointer;">Đặt hàng</button>

            <a href="{{ url('gio-hang') }}" class="_link-detail-cart cart" target="_blank" style="display: none;">Vào giỏ hàng</a>

            <div class="note-img"></div>
        </div>
    </div>

    <span class="pull-right hidden">
        <b>
            Phiên bản <span class="_addon-version">1.0</span>
        </b>
    </span>

</div>