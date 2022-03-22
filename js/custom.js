var myCarousel=document.querySelector('#carouselExampleIndicators')
var carousel=new bootstrap.Carousel(myCarousel,{
  interval:5000,
  wrap:false,
})

myCarousel.addEventListener('slide.bs.carousel',function()
  {
alert(1);
  }
)


