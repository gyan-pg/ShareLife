// jquery読み込み
window.$ = window.jQuery = require('jquery')

$(function () {
  const $window = $(window)
  
  // スクロールで下ナビゲーションのスタイルが変わるやつ
  const $nav = $('.js-nav')
  const $nav_item = $('.js-nav-item')
  const nav_height = $('.js-nav').height()
  let windowHeight = $window.height()
  $window.scroll(function () {
    let scroll = $(this).scrollTop()
    if (scroll > windowHeight - nav_height) {
      $nav_item.addClass('active')
      $nav.addClass('active')
    } else {
      $nav_item.removeClass('active')
      $nav.removeClass('active')
    }
  })

  // heroのメッセージがふわっとするやつ
  const $top_message = $('.js-top__message')
  const $top_message_title = $('.js-top__message--title')
  // 読み込み時のみ実行
  $top_message.addClass('active')
  setTimeout(function () {
    $top_message_title.addClass('active')
  },1000)

  // チーム結成後の画面の説明
  const $slide1 = $('.js-slide1')
  const $slide2 = $('.js-slide2')
  const $slide3 = $('.js-slide3')

  // 制御用のボタン
  const $showBtn1 = $('.js-show-slide1')
  const $showBtn2 = $('.js-show-slide2')
  const $showBtn3 = $('.js-show-slide3')

  $showBtn1.addClass('active')
  $slide2.hide()
  $slide3.hide()

  const duration = 300
  // スライドの切り替え
  $showBtn1.on('click', function(){
    $(this).addClass('active')
    $showBtn2.removeClass('active')
    $showBtn3.removeClass('active')
    setTimeout(() => {
      $slide1.fadeIn(duration)
    }, duration + 50)
    $slide2.fadeOut(duration)
    $slide3.fadeOut(duration)
  })

  $showBtn2.on('click', function(){
    $(this).addClass('active')
    $showBtn1.removeClass('active')
    $showBtn3.removeClass('active')
    setTimeout(() => {
      $slide2.fadeIn(duration)
    }, duration + 50)
    $slide3.fadeOut(duration)
    $slide1.fadeOut(duration)
  })

  $showBtn3.on('click', function(){
    $(this).addClass('active')
    $showBtn1.removeClass('active')
    $showBtn2.removeClass('active')
    setTimeout(() => {
      $slide3.fadeIn(duration)
    }, duration + 50)
    $slide1.fadeOut(duration)
    $slide2.fadeOut(duration)
  })

})