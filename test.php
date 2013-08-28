<?php
require_once 'includes/DB.class.php';
$sql = "select filename, uploadTime, count(distinct materialCode, orderNumber, orderSubitemNumber) as distinctMaterialCodeCount,
		sum(isFinished) as finishedCount
		from
	(
	select materialCode, orderNumber, orderSubitemNumber, filename, uploadTime
	from sss_main
	group by filename, materialCode, orderNumber, orderSubitemNumber
	)
	as mcInfoTable

left outer join

	(
	select materialCode, orderNumber, orderSubitemNumber, (sumCount = coalesce(chukuCount, 0) + coalesce(directCount, 0)) as isFinished
	from(
		select * from
		(
			(
			select materialCode, orderNumber, orderSubitemNumber, sum(`count`) as sumCount
			from sss_main
			group by materialCode, orderNumber, orderSubitemNumber
			)
			as sumCountTable

		left join

			(
			select materialCode, orderNumber, orderSubitemNumber, sum(`halfChukuCount`) as chukuCount
			from
				(
					(select materialCode, orderNumber, orderSubitemNumber, sum(`count`) as halfChukuCount
					from sss_fache
					where phase = '出库'
					group by materialCode, orderNumber, orderSubitemNumber
					)
					
					union all
					
					(select materialCode, orderNumber, orderSubitemNumber, sum(`count`) as halfChukuCount
					from sss_fachuan
					group by materialCode, orderNumber, orderSubitemNumber
					)
				)as nativeChukuCountTable
			group by materialCode, orderNumber, orderSubitemNumber
			)
			as chukuCountTable
		using (materialCode, orderNumber, orderSubitemNumber)

		left join

			(
			select materialCode, orderNumber, orderSubitemNumber, sum(`count`) as directCount
			from sss_fache
			where phase='销售' 
			group by materialCode, orderNumber, orderSubitemNumber
			)
			as directCountTable

		using (materialCode, orderNumber, orderSubitemNumber)
		)
	   )
		as CountTable
	)as isFinishedTable
using (materialCode, orderNumber, orderSubitemNumber)
group by filename
order by uploadTime desc;";
$result = DB::query ( $sql );
echo DB::num_rows ( $result );
while ( $rows = $result->fetch_assoc () ) {
	print_r ( $rows );
	echo "<br>";
}
?>