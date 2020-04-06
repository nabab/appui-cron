<!-- HTML Document -->

<bbn-router :autoload="true"
            :nav="true"
>
	<bbns-container :title="_('Home')"
           :loaded="false"
           :load="true"
           icon="nf nf-fa-home"
           :pinned="true"
           url="home">
  </bbns-container>
</bbn-router>