<section class="wrap_department">
    <div class="form_add_department_wrap">
        <h3 class="from_add_department_heading">Thêm ngày nghỉ</h3>
        <form action="./Holiday/createHoliday" class="form_add_department" method="POST">
            <div class="one_field">
                <label for="date">Chọn ngày</label>
                <input name="date" type="date" id="date" class="maPb" value="<?= PrintDisplay::printValue($data, 'date') ?>">
                <p class="error_from"><?php PrintDisplay::printError($data, 'date') ?></p>
            </div>
            <div class="one_field">
                <label for="">Tên Ngày Nghỉ</label>
                <input name="name_holiday" type="text" id="name_department" placeholder="Tên ngày nghỉ" class="name_department" value="<?= PrintDisplay::printValue($data, 'name_holiday') ?>">
                <p class="error_from"><?php PrintDisplay::printError($data, 'name_holiday') ?></p>
            </div>
            <button class="global_btn">Thêm Ngày Nghỉ</button>
        </form>
    </div>
    <div class="list_department hide_scroll">
        <table class="table ">
            <thead class="list_department_header">
                <tr>
                    <th>Ngày Nghỉ</th>
                    <th>Tên Ngày Nghỉ</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody class="list_department_show">

            </tbody>

        </table>
    </div>
</section>
<script defer src="./public/js/holiday.js"></script>