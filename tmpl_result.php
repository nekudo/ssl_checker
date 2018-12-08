<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSL Certificate Info</title>
    <style>
        body { font-family: "Helvetica Neue",Helvetica,Arial,sans-serif; }
        .container { width: 1200px; margin: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead { text-align: left; }
        tbody > tr:nth-of-type(odd) { background-color: rgba(0,0,0,0.05); }
        tbody > tr:hover { background-color: #93a9bc; }
        th, td { padding: .45em .4em; }
        .label { color: #fff; border-radius: .2em; padding: .15em .4em; }
        .label.success { background-color: #00a65a; }
        .label.warning { background-color: #f39c12; }
        .label.error { background-color: #dd4b39; }
    </style>
</head>

<body>
    <div class="container">
        <h1>SSL Certificate Information</h1>
        <table>
            <thead>
                <tr>
                    <th>Domain</th>
                    <th>Subject</th>
                    <th>Issuer</th>
                    <th>Valid From</th>
                    <th>Valid To</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($this->certificateData as $item): ?>
                    <tr>
                        <td><?php echo $item['domain']; ?></td>
                        <td><?php echo $item['subject']; ?></td>
                        <td><?php echo $item['issuer']; ?></td>
                        <td><?php echo $item['valid_from']; ?></td>
                        <td><?php echo $item['valid_to']; ?></td>
                        <td>
                            <?php if ($item['state'] === 'error'): ?>
                                <small class="label error">Error</small>
                            <?php elseif ($item['state'] === 'warning'): ?>
                                <small class="label warning">Expiering Soon</small>
                            <?php else: ?>
                                <small class="label success">OK</small>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
