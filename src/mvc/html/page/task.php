<bbn-router :autoload="true"
            :nav="true"
>
	<bbns-container :title="_('Home')"
           :load="false"
           icon="nf nf-fa-home"
           url="home"
           component="appui-cron-task"
           :source="source"
           :notext="true"
  ></bbns-container>
</bbn-router>