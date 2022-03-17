
<style>
    .base-color{
        height:20px;
        width:20px;
        display: inline-block;
        background-color: {{ $data->base_color }};
    }
    .text-color{
        height:20px;
        width:20px;
        display: inline-block;
        background-color: {{ $data->text_color }};
    }
    .scroll-color{
        height:20px;
        width:20px;
        display: inline-block;
        background-color: {{ $data->scroll_color }};
    }
    .success-color{
        height:20px;
        width:20px;
        display: inline-block;
        background-color: {{ $data->success_color }};
    }
    .warning-color{
        height:20px;
        width:20px;
        display: inline-block;
        background-color: {{ $data->warning_color }};
    }
    .danger-color{
        height:20px;
        width:20px;
        display: inline-block;
        background-color: {{ $data->danger_color }};
    }
    .color_div {
        justify-content: left;
        display: grid;
        max-width: 100%;
        max-height: 200px;
        align-items: center;
    }
    </style>
    
    <table class="table-borderless clone_line_table">
        <tr>
            <td class="info_tbl">Base color:</td>
            <td><div class="base-color"></div></td>
        </tr>
        <tr>
            <td class="info_tbl">Scroll color:</td>
            <td><div class="scroll-color"></div></td>
        </tr>
        <tr>
            <td class="info_tbl">Text color:</td>
            <td><div class="text-color"></div></td>
        </tr>
        <tr>
            <td class="info_tbl">Success color:</td>
            <td><div class="success-color"></div></td>
        </tr>
        <tr>
            <td class="info_tbl">Warning color:</td>
            <td><div class="warning-color"></div></td>
        </tr>
        <tr>
            <td class="info_tbl">Danger color:</td>
            <td><div class="danger-color"></div></td>
        </tr>
    </table>