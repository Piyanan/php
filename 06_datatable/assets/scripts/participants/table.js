let table = new DataTable('#participants_table', {
  processing: true,
  serverSide: true,
  order: [4, "desc"],
  ajax: {
    url: `${base_url}/backend/participants/datatable.php`,
    type: 'POST',
  },
  columns: [
    {
      render: function (data, type, row, meta) {
        return meta.row + meta.settings._iDisplayStart + 1;
      },
      orderable: false,
      width: '20px'
    },
    {
      data: 'photo',
      "fnCreatedCell": function (nTd, sData, oData, iRow, iCol) {
        $(nTd).html(`<image src="${base_url}/uploads/${oData.photo}.jpg" width='100px'>`);
      },
      orderable: false,
    },
    { data: 'name' },
    { data: 'email' },
    { data: 'register_at' },
  ]
});