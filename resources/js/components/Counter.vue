<template>
  <div class="col-12 col-md-12 col-lg-6 col-xl-3 mb-2">
        <div :class="'card border-' + color + ' shadow h-100'">
            <div class="card-body" style="padding: 0.7rem !important;">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div :class="'text-xs font-weight-bold text-' + color + ' text-uppercase mb-1'">{{ title }}
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count }}</div>
                    </div>
                    <div class="col-auto">
                        <i :class="'fas fa-' + icon + ' fa-2x text-' + color"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    props: ["title", "icon", "color", "count"],
  };
</script>

<style>
  .counter {
    color: #eb3b5a;
    font-family: "Muli", sans-serif;
    width: 100px;
    height: 100px;
    text-align: center;
    border-radius: 100%;
    padding: 35px 15px 20px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
  }
  .counter:before,
  .counter:after {
    content: "";
    background: #fff;
    width: 90%;
    height: 90%;
    border-radius: 100%;
    box-shadow: -5px 5px 5px rgba(0, 0, 0, 0.3);
    transform: translateX(-50%) translateY(-50%);
    position: absolute;
    top: 50%;
    left: 50%;
    z-index: -1;
  }
  .counter:after {
    background: linear-gradient(45deg, #b81242 49%, #d74a75 50%);
    width: 100%;
    height: 100%;
    box-shadow: none;
    transform: translate(0);
    top: 0;
    left: 0;
    z-index: -2;
    clip-path: polygon(50% 50%, 50% 0, 100% 0, 100% 100%, 0 100%, 0 50%);
  }
  .counter .counter-icon {
    color: #fff;
    background: linear-gradient(45deg, #b81242 49%, #d74a75 50%);
    font-size: 15px;
    line-height: 35px;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 1;
    transition: all 0.3s;
  }
  .counter .counter-icon i.fa {
    transform: rotateX(0deg);
    transition: all 0.3s ease 0s;
  }
  .counter:hover .counter-icon i.fa {
    transform: rotateX(360deg);
  }
  .counter h3 {
    font-size: 15px;
    font-weight: 700;
    text-transform: uppercase;
    margin: 0 0 0px;
  }
  .counter .counter-value {
    font-size: 20px;
    font-weight: 700;
  }
  .counter.primary {
    color: #307bff;
  }
  .counter.primary:after,
  .counter.primary .counter-icon {
    background: linear-gradient(45deg, #307bff 49%, #64c1ff 50%);
  }
  .counter.secondary {
    color: #6c757e;
  }
  .counter.secondary:after,
  .counter.secondary .counter-icon {
    background: linear-gradient(45deg, #6c757e 49%, #585c5f 50%);
  }
  .counter.info {
    color: #24a2b8;
  }
  .counter.info:after,
  .counter.info .counter-icon {
    background: linear-gradient(45deg, #24a2b8 49%, #39c3db 50%);
  }
  .counter.success {
    color: #28a745;
  }
  .counter.success:after,
  .counter.success .counter-icon {
    background: linear-gradient(45deg, #28a745 49%, #40da64 50%);
  }
  .counter.warning {
    color: #fbc109;
  }
  .counter.warning:after,
  .counter.warning .counter-icon {
    background: linear-gradient(45deg, #fbc109 49%, #f8d257 50%);
  }
  .counter.danger {
    color: #dc3545;
  }
  .counter.danger:after,
  .counter.danger .counter-icon {
    background: linear-gradient(45deg, #dc3545 49%, #ee5968 50%);
  }
</style>