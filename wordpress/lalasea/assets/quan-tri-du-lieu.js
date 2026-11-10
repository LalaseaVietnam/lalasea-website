/**
 * Màn hình "Thông tin nhà hàng" trong /wp-admin (inc/quan-tri-du-lieu.php):
 * thêm / xoá / đổi thứ tự mục trong danh sách, chọn ảnh từ Thư viện.
 */
jQuery( function ( $ ) {
	var dem = 0;
	var goc = window.lalaseaGoc || '';

	// "+ Thêm": chép mẫu trong <template>, thay mã __iN__ bằng một số chưa dùng
	$( document ).on( 'click', '.ls-them', function () {
		var danhSach = $( this ).closest( '.ls-ds' );
		var ma = danhSach.data( 'ma' );
		var html = danhSach.children( 'template' ).html().split( ma ).join( 'm' + Date.now() + dem++ );
		danhSach.children( '.ls-cac-muc' ).append( html );
	} );

	$( document ).on( 'click', '.ls-xoa', function () {
		if ( window.confirm( 'Xoá mục này?' ) ) {
			$( this ).closest( '.ls-muc' ).remove();
		}
	} );

	$( document ).on( 'click', '.ls-len', function () {
		var muc = $( this ).closest( '.ls-muc' );
		muc.prev( '.ls-muc' ).before( muc );
	} );

	$( document ).on( 'click', '.ls-xuong', function () {
		var muc = $( this ).closest( '.ls-muc' );
		muc.next( '.ls-muc' ).after( muc );
	} );

	// Ảnh chọn từ Thư viện có địa chỉ đầy đủ — lưu dạng /duong-dan để dùng chung với schema
	$( document ).on( 'click', '.ls-chon-anh', function ( e ) {
		e.preventDefault();
		var hang = $( this ).closest( '.ls-anh-hang' );
		var khung = wp.media( { title: 'Chọn ảnh', multiple: false, library: { type: 'image' } } );
		khung.on( 'select', function () {
			var url = khung.state().get( 'selection' ).first().toJSON().url;
			if ( url.indexOf( goc ) === 0 ) {
				url = url.slice( goc.length );
			}
			hang.find( 'input' ).val( url );
			hang.find( '.ls-xem' ).html( $( '<img alt>' ).attr( 'src', goc + url ) );
		} );
		khung.open();
	} );
} );
