<?php
/**
 * The sample of Kendo UI
 *
 * @copyright   Kevin
 * @license    Free
 * @author     Kevin
 * @package     kevinkendoui
 * @version    1.0
 * @link        http://www.zentao.net
 */
?>
<?php include '../../kevincom/view/header.html.php'; ?>
<link rel="stylesheet" href="<?php echo $jsRoot; ?>kendoui/styles/kendo.common.min.css" />
<link rel="stylesheet" href="<?php echo $jsRoot; ?>kendoui/styles/kendo.default.min.css" />
<link rel="stylesheet" href="<?php echo $jsRoot; ?>kendoui/styles/kendo.dataviz.min.css" />
<link rel="stylesheet" href="<?php echo $jsRoot; ?>kendoui/styles/kendo.dataviz.default.min.css" />

<script src="<?php echo $jsRoot; ?>kendoui/js/jquery.min.js"></script>
<script src='<?php echo $jsRoot; ?>kendoui/js/kendo.all.min.js' type='text/javascript'></script>
<script src='<?php echo $jsRoot; ?>kendoui/js/cultures/kendo.culture.zh-CN.min.js'></script>
<div>
    <div style='margin-bottom: 5px'>
        <span id="kevinkendoui_Computer" class="k-button" onclick="kevinkendoui_Computer_onclick(event)">computer</span>
        <span id="kevinkendoui_All" class="k-button" onclick="kevinkendoui_All_onclick(event)">All record</span>
        <span style="margin-left: 50px" id="Grid_refresh" class="k-button" onclick="Grid_refresh_onclick(event)">refreash</span>
    </div>
    <div id="grid"></div>
</div>
<?php include '../../kevincom/view/footer.html.php'; ?>
<script>
    var record = 0;
    $(document).ready(function() {
        $("#grid").kendoGrid();
        $("#kevinkendoui_All").click();
    });

    /**
     * 刷新
     */
    function Grid_refresh_onclick() {
        $("#grid").data("kendoGrid").dataSource.read();
    }

    /**
     * count by computer
     */
    function kevinkendoui_Computer_onclick() {
        record = 0;
        $("#grid").data("kendoGrid").destroy();
        $("#grid").html("");
        $("#grid").kendoGrid({
            dataSource: {
                transport: {
                    read: {
                        dataType: "json",
                        url: createLink("kevinkendoui", "getlist","type=Computer")
                    }
                },
                //pageSize: 999999999,
                schema: {
                    model: {
                        fields: {
                            sumCount: {type: "number", editable: false}
                        }
                    }
                }
            },
            dataBinding: function() {
                record = (this.dataSource.page() - 1) * this.dataSource.pageSize();
            },
            sortable: true,
            filterable: {
                extra: false,
                operators: {
                    string: {
                        contains: "包含",
                        doesnotcontain: "不包含",
                        eq: "等于",
                        neq: "不等于"
                    }
                },
                messages: {
                    info: "筛选:",
                    and: "和",
                    or: "或",
                    filter: "确定",
                    clear: "清除"
                }
            },
            selectable: "row",
            pageable: {
                pageSize: 20,
                pageSizes: [20, 50, 100, 200, 500],
                messages: {
                    display: "{0}-{1} 共{2}个",
                    empty: "无数据",
                    itemsPerPage: "项每页"
                }
            },
            resizable: true,
            columns: [{
                    template: "#= ++record #",
                    title: "No.",
                    width: 60
                }, {
                    field: "sumCount",
                    title: "Counter",
                    width: 80
                }, {
                    field: "computer",
                    title: "Computer"
                }]
        });
    }

    /**
     * 详细记录分类统计
     */
    function kevinkendoui_All_onclick() {
        record = 0;
        $("#grid").data("kendoGrid").destroy();
        $("#grid").html("");
        $("#grid").kendoGrid({
            dataSource: {
                transport: {
                    read: {
                        dataType: "json",
                        url: createLink("kevinkendoui", "getlist","type=All")
                    }
                },
            },
            dataBinding: function() {
                record = (this.dataSource.page() - 1) * this.dataSource.pageSize();
            },
            sortable: true,
            filterable: {
                extra: false,
                operators: {
                    string: {
                        contains: "包含",
                        doesnotcontain: "不包含",
                        eq: "等于",
                        neq: "不等于"
                    }
                },
                messages: {
                    info: "筛选:",
                    and: "和",
                    or: "或",
                    filter: "确定",
                    clear: "清除"
                }
            },
            selectable: "row",
            pageable: {
                pageSize: 20,
                pageSizes: [20, 50, 100, 200, 500],
                messages: {
                    display: "{0}-{1} 共{2}个",
                    empty: "无数据",
                    itemsPerPage: "项每页"
                }
            },
            resizable: true,
            columns: [{
                    template: "#= ++record #",
                    title: "id",
                    width: 60
                }, {
                    field: "user",
                    title: "user",
                    width: 80
                }, {
                    field: "computer",
                    title: "Computer",
                    width: 150
                }, {
                    field: "activetime",
                    title: "active Time"
                }]
        });
    }
</script>

